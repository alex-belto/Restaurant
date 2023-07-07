<?php

namespace App\Services\Tips;

use App\Entity\Kitchener;
use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\TipsStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Represents a tip distribution strategy where the waiter who served the order
 * receives 60% of the total tips, and the remaining amount is distributed among all staff members.
 */
class TipsWaiterStrategy implements TipsStrategyInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * @param Order $order
     * @throws \Exception
     */
    public function splitTips(Order $order): void
    {
        $tips = $order->getTips();
        $restaurant = $order->getWaiter()->getRestaurant();
        $headWaiter = $order->getWaiter();
        $orderOwnerTips = ($tips / 100) * 60;
        $waiters = $restaurant->getWaiters();
        $kitcheners = $restaurant->getKitcheners();
        $amountOfStaff = count($waiters) + count($kitcheners) - 1;
        $tipsForEach = ($tips - $orderOwnerTips) / $amountOfStaff;

        /** @var Kitchener $kitchener */
        foreach ($kitcheners as $kitchener) {
            $kitchenerTips = $kitchener->getTips() + $tipsForEach;
            $kitchener->setTips($kitchenerTips);
        }
        $this->em->flush();

        /** @var Waiter $waiter */
        foreach ($waiters as $waiter) {
            if ($waiter === $headWaiter) {
                $waiter->setTips($orderOwnerTips);
            }
            $waiterTips = $waiter->getTips() + $tipsForEach;
            $waiter->setTips($waiterTips);
        }
        $this->em->flush();

    }
}