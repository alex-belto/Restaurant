<?php

namespace App\Enum;

enum ClientStatus
{
    case WITHOUT_ORDER;
    case ORDER_PLACED;
    case ORDER_PAYED;

    public function getIndex(): int
    {
        return match($this) {
            self::WITHOUT_ORDER => 1,
            self::ORDER_PLACED => 2,
            self::ORDER_PAYED => 3
        };
    }

}