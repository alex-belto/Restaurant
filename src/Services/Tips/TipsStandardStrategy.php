<?php

namespace App\Services\Tips;

use App\Entity\Order;
use App\Interfaces\StaffInterface;
use App\Interfaces\TipsStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Represents the standard strategy for distributing tips,
 * where the tips are divided equally among all staff members.
 */
class TipsStandardStrategy implements TipsStrategyInterface
{
    private EntityManagerInterface $em;

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
        $waiters = $restaurant->getWaiters()->toArray();
        $kitcheners = $restaurant->getKitcheners()->toArray();
        $staffs = array_merge($waiters, $kitcheners);
        $amountOfStaff = count($waiters) + count($kitcheners);
        $tipsForEach = $order->getTips() / $amountOfStaff;

        /** @var StaffInterface $staff */
        foreach ($staffs as $staff) {
            $staffTips = $staff->getTips() + $tipsForEach;
            $staff->setTips($staffTips);
        }

        $this->em->flush();
    }
}