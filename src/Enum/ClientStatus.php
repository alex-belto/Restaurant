<?php

namespace App\Enum;

enum ClientStatus: int
{
    case WITHOUT_ORDER = 1;
    case ORDER_PLACED = 2;
    case ORDER_PAYED = 3;
}