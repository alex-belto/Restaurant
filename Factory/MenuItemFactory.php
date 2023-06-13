<?php

namespace App\Factory;

use App\Entity\Dish;
use App\Entity\Drink;
use JetBrains\PhpStorm\Pure;

class MenuItemFactory
{
    public function createDish(string $name, float $price, string $time): Dish
    {
        return new Dish($name, $price, $time);
    }

    public function createDrink(string $name, float $price, string $time): Drink
    {
        return new Drink($name, $price, $time);
    }
}