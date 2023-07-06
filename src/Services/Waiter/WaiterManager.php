<?php

namespace App\Services\Waiter;

use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\OrderManagerInterface;
use App\Services\Staff\StaffManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles order processing on the waiter's side.
 */
class WaiterManager implements OrderManagerInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var StaffManager
     */
    private $staffManager;

    /**
     * @param EntityManagerInterface $em
     * @param StaffManager $staffManager
     */
    public function __construct(
        EntityManagerInterface $em,
        StaffManager $staffManager
    ) {
        $this->em = $em;
        $this->staffManager = $staffManager;
    }

    /**
     * @throws \Exception
     */
    public function processingOrder(Order $order): void
    {
        /** @var Waiter $waiter */
        $waiter = $this->staffManager->chooseStaff('waiter');
        $waiter->addOrder($order);
        $order->setStatus(Order::READY_TO_KITCHEN);
        $this->em->flush();
    }

    public function bringFood(Order $order): void
    {
        $order->setStatus(Order::DONE);
    }

}