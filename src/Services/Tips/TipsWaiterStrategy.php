<?php

namespace App\Services\Tips;

use App\Entity\Kitchener;
use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\StaffInterface;
use App\Interfaces\TipsStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Represents a tip distribution strategy where the waiter who served the order
 * receives 60% of the total tips, and the remaining amount is distributed among all staff members.
 */
class TipsWaiterStrategy implements TipsStrategyInterface
{
    /**
     * @throws \Exception
     */
    public function splitTips(Order $order): void
    {
        $tips = $order->calculateTips();
        $restaurant = $order->getWaiter()->getRestaurant();
        $headWaiter = $order->getWaiter();
        $orderOwnerTips = ($tips / 100) * 60;
        $staffs = array_merge($restaurant->getWaiters(), $restaurant->getKitcheners());
        $amountOfStaff = count($staffs) - 1;
        $tipsForEach = ($tips - $orderOwnerTips) / $amountOfStaff;

        /** @var StaffInterface $staff */
        foreach ($staffs as $staff) {
            if ($staff === $headWaiter) {
                $staff->setTips($orderOwnerTips);
            }
            $staffTips = $staff->getTips() + $tipsForEach;
            $staff->setTips($staffTips);
        }
    }
}