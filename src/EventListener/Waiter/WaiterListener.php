<?php

namespace App\EventListener\Waiter;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Waiter;
use App\Enum\ClientStatus;
use App\Enum\OrderStatus;
use App\Services\Staff\StaffResolver;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Listening to the client, we pass the order to the chef after its status has been changed.
 * After listening to the order, we pass it to the waiter for processing once its status changes to "READY_TO_WAITER."
 */
class WaiterListener
{
    private StaffResolver $staffResolver;
    private EntityManagerInterface $em;

    public function __construct(
        StaffResolver $staffResolver,
        EntityManagerInterface $em,
    ) {
        $this->staffResolver = $staffResolver;
        $this->em = $em;
    }

    public function processOrderByWaiter(Client $client): void
    {
        if ($client->getStatus() !== ClientStatus::ORDER_PLACED) {
            return;
        }
            /** @var Waiter $waiter */
            $waiter = $this->staffResolver->chooseStaff('waiter');
            $order = $client->getConnectedOrder();
            $waiter->addOrder($order);
            $order->setStatus(OrderStatus::READY_TO_KITCHEN);
            $this->em->flush();
    }

    public function deliveryOrder(Order $order): void
    {
        if ($order->getStatus() !== OrderStatus::READY_TO_WAITER) {
            return;
        }

        $kitchener = $order->getKitchener();
        $kitchener->removeOrder($order);
        $order->setStatus(OrderStatus::DELIVERED);
        $this->em->flush();
    }
}