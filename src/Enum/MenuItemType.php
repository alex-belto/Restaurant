<?php

namespace App\Enum;

enum MenuItemType
{
    case DISH;
    case DRINK;

    public function getIndex(): int
    {
        return match ($this) {
            self::DISH => 1,
            self:: DRINK => 2
        };
    }
}