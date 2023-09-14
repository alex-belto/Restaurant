<?php

namespace App\Enum;

enum OrderStatus: int
{
    case READY_TO_WAITER = 1;
    case READY_TO_KITCHEN = 2;
    case DELIVERED = 3;
    case DONE = 4;
}