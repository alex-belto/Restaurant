<?php

namespace App\Services\Kitchener;

use App\Entity\Kitchener;
use App\Entity\Order;
use App\Interfaces\OrderManagerInterface;
use App\Services\Staff\StaffManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles order processing on the chef's side.
 */
class KitchenerManager implements OrderManagerInterface
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
        /** @var Kitchener $kitchener */
        $kitchener = $this->staffManager->chooseStaff('kitchener');
        $kitchener->addOrder($order);
        $order->setStatus(Order::READY_TO_WAITER);
        $this->em->flush();
    }

}