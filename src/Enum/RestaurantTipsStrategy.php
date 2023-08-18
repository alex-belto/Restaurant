<?php

namespace App\Enum;

enum RestaurantTipsStrategy
{
    case TIPS_STANDARD_STRATEGY;
    case TIPS_WAITER_STRATEGY;

    public function getIndex(): int
    {
        return match ($this) {
            self::TIPS_STANDARD_STRATEGY => 1,
            self:: TIPS_WAITER_STRATEGY => 2
        };
    }
}