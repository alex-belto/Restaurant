<?php

namespace App\EventListener\Waiter;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\Waiter\WaiterOrderProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Listening to the client, we pass the order to the chef after its status has been changed.
 * After listening to the order, we pass it to the waiter for processing once its status changes to "READY_TO_WAITER."
 */
class WaiterListener
{
    private WaiterOrderProcessor $waiterOrderProcessor;

    private EntityManagerInterface $em;

    public function __construct(
        WaiterOrderProcessor   $waiterOrderProcessor,
        EntityManagerInterface $em,
    ) {
        $this->waiterOrderProcessor = $waiterOrderProcessor;
        $this->em = $em;
    }

    public function processOrderByWaiter(Client $client, LifecycleEventArgs $eventArgs): void
    {
        if ($client->getStatus() === Client::ORDER_PLACED) {
            $this->waiterOrderProcessor->processingOrder($client->getConnectedOrder());
        }
    }

    public function deliveryOrder(Order $order): void
    {
        if ($order->getStatus() !== Order::READY_TO_WAITER) {
            return;
        }

        $kitchener = $order->getKitchener();
        $kitchener->removeOrder($order);
        $this->waiterOrderProcessor->bringFood($order);
        $this->em->flush();
    }
}