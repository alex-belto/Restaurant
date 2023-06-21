<?php

namespace App\Services\Kitchener;

use App\Entity\Kitchener;
use App\Entity\Order;
use App\Interfaces\OrderManagerInterface;
use App\Services\Staff\ChooseStaff;
use Doctrine\ORM\EntityManagerInterface;

class KitchenerManager implements OrderManagerInterface
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

    /**
     * @throws \Exception
     */
    public function processingOrder(Order $order): void
    {
        /** @var Kitchener $kitchener */
        $kitchener = $this->chooseStaff->chooseStaff('kitchener');
        $kitchener->addOrder($order);
        $order->setStatus(Order::READY_TO_WAITER);
        $this->em->flush();
    }

}