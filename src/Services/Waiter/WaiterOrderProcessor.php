<?php

namespace App\Services\Waiter;

use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\OrderManagerInterface;
use App\Services\Staff\StaffResolver;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles order processing on the waiter's side.
 */
class WaiterOrderProcessor implements OrderManagerInterface
{
    private EntityManagerInterface $em;

    private StaffResolver $staffResolver;

    /**
     * @param EntityManagerInterface $em
     * @param StaffResolver $staffResolver
     */
    public function __construct(
        EntityManagerInterface $em,
        StaffResolver $staffResolver
    ) {
        $this->em = $em;
        $this->staffResolver = $staffResolver;
    }

    /**
     * @throws \Exception
     */
    public function processingOrder(Order $order): void
    {
        /** @var Waiter $waiter */
        $waiter = $this->staffResolver->chooseStaff('waiter');
        $waiter->addOrder($order);
        $order->setStatus(Order::READY_TO_KITCHEN);
        $this->em->flush();
    }

    public function bringFood(Order $order): void
    {
        $order->setStatus(Order::DONE);
    }

}