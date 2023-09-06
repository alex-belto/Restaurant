<?php

namespace App\Enum;

enum RestaurantTipsStrategy: int
{
    case TIPS_STANDARD_STRATEGY = 1;
    case TIPS_WAITER_STRATEGY = 2;
}