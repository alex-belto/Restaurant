<?php

namespace App\Services\Kitchener;

use App\Entity\Kitchener;
use App\Entity\Order;
use App\Interfaces\OrderManagerInterface;
use App\Services\Staff\StaffResolver;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles order processing on the chef's side.
 */
class KitchenerOrderProcessor implements OrderManagerInterface
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
        /** @var Kitchener $kitchener */
        $kitchener = $this->staffResolver->chooseStaff('kitchener');
        $kitchener->addOrder($order);
        $order->setStatus(Order::READY_TO_WAITER);
        $this->em->flush();
    }

}