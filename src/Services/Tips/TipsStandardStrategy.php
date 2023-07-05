<?php

namespace App\Services\Tips;

use App\Entity\Kitchener;
use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\TipsStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Represents the standard strategy for distributing tips,
 * where the tips are divided equally among all staff members.
 */
class TipsStandardStrategy implements TipsStrategyInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em,
    ) {
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    public function splitTips(Order $order): void
    {
        $restaurant = $order->getWaiter()->getRestaurant();
        $waiters = $restaurant->getWaiters();
        $kitcheners = $restaurant->getKitcheners();
        $amountOfStaff = count($waiters) + count($kitcheners);
        $tipsForEach = $order->getTips() / $amountOfStaff;

        /** @var Kitchener $kitchener */
        foreach ($kitcheners as $kitchener) {
            $kitchenerTips = $kitchener->getTips() + $tipsForEach;
            $kitchener->setTips($kitchenerTips);
        }

        /** @var Waiter $waiter */
        foreach ($waiters as $waiter) {
            $waiterTips = $waiter->getTips() + $tipsForEach;
            $waiter->setTips($waiterTips);
        }
        $this->em->flush();

    }
}