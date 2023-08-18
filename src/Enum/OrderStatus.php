<?php

namespace App\Enum;

enum OrderStatus
{
    case READY_TO_WAITER;
    case READY_TO_KITCHEN;
    case DONE;

    public function getIndex(): int
    {
        return match ($this) {
            self::READY_TO_WAITER => 1,
            self:: READY_TO_KITCHEN => 2,
            self:: DONE => 3
        };
    }
}