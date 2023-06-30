<?php

namespace App\Services\Waiter;

use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\OrderManagerInterface;
use App\Services\Staff\StaffManager;
use Doctrine\ORM\EntityManagerInterface;

class WaiterManager implements OrderManagerInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var StaffManager
     */
    private $chooseStaff;

    /**
     * @param EntityManagerInterface $em
     * @param StaffManager $chooseStaff
     */
    public function __construct(
        EntityManagerInterface $em,
        StaffManager $chooseStaff
    ) {
        $this->em = $em;
        $this->chooseStaff = $chooseStaff;
    }

    /**
     * @throws \Exception
     */
    public function processingOrder(Order $order): void
    {
        /** @var Waiter $waiter */
        $waiter = $this->chooseStaff->chooseStaff('waiter');
        $waiter->addOrder($order);
        $order->setStatus(Order::READY_TO_KITCHEN);
        $this->em->flush();
    }

    public function bringFood(Order $order): void
    {
        $order->setStatus(Order::DONE);
    }

}