<?php

namespace App\Enum;

/**
 * Representing constant restaurant data.
 */
enum RestaurantData: int
{
    case WORK_HOURS = 8;
    case MAX_VISITORS_PER_HOUR = 50;
}