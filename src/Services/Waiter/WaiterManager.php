<?php

namespace App\Services\Waiter;

use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\OrderManagerInterface;
use App\Services\Staff\ChooseStaff;
use Doctrine\ORM\EntityManagerInterface;

class WaiterManager implements OrderManagerInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ChooseStaff
     */
    private $chooseStaff;

    /**
     * @param EntityManagerInterface $em
     * @param ChooseStaff $chooseStaff
     */
    public function __construct(
        EntityManagerInterface $em,
        ChooseStaff $chooseStaff
    ) {
        $this->em = $em;
        $this->chooseStaff = $chooseStaff;
    }

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
        $client = $order->getClient();
        $order->setStatus(Order::DONE);
        $waiter = $order->getWaiter();
        $waiter->removeOrder($order);
        $this->em->flush();
    }

}