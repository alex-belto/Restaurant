<?php

namespace App\Services\Tips;

use App\Entity\Order;
use App\Entity\Restaurant;

/**
 * Selects a tip distribution strategy and distributes the tips among the staff members.
 */
class TipsDistributor
{
    private TipsStandardStrategy $tipsStandardStrategy;

    private TipsWaiterStrategy $tipsWaiterStrategy;

    public function __construct(
        TipsStandardStrategy $tipsStandardStrategy,
        TipsWaiterStrategy $tipsWaiterStrategy
    ) {
        $this->tipsStandardStrategy = $tipsStandardStrategy;
        $this->tipsWaiterStrategy = $tipsWaiterStrategy;
    }

    /**
     * @throws \Exception
     */
    public function splitTips(Order $order): void
    {
        $restaurant = $order->getWaiter()->getRestaurant();
        $tipsStrategy = match ($restaurant->getTipsStrategy()) {
            Restaurant::TIPS_STANDARD_STRATEGY => $this->tipsStandardStrategy,
            Restaurant::TIPS_WAITER_STRATEGY => $this->tipsWaiterStrategy,
            default => 'wrong tips strategy type!'
        };
        $tipsStrategy->splitTips($order);
    }
}