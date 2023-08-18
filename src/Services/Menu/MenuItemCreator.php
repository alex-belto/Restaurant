<?php

namespace App\Services\Menu;

use App\Entity\MenuItem;
use App\Enum\MenuItemType;

/**
 * Responsible for creating menu items.
 */
class MenuItemCreator
{
    public function createDrink(): MenuItem
    {
        $name = $this->generateRandomDrinkName();
        $price = rand(1, 3);
        $time = rand(5, 10);

        $drink = new MenuItem();
        $drink->setName($name);
        $drink->setType(MenuItemType::DRINK->getIndex());
        $drink->setPrice($price);
        $drink->setTime($time . 'min');

        return $drink;
    }

    public function createDish(): MenuItem
    {
        $name = $this->generateRandomDishName();
        $price = rand(4, 15);
        $time = rand(20, 60);

        $dish = new MenuItem();
        $dish->setName($name);
        $dish->setType(MenuItemType::DISH->getIndex());
        $dish->setPrice($price);
        $dish->setTime($time . 'min');

        return $dish;
    }

    private function generateRandomDishName(): string
    {
        $dishTypes = ['Pasta', 'Soup', 'Salad', 'Pie', 'Potato'];
        $dishAdjectives = ['Italian', 'Hot', 'Cheesy', 'Ukrainian', 'Spicy'];
        $dishIngredients = ['with chicken', 'with seafood', 'with mushrooms', 'with cheese', 'with vegetables'];

        $randomType = $dishTypes[array_rand($dishTypes)];
        $randomAdjective = $dishAdjectives[array_rand($dishAdjectives)];
        $randomIngredient = $dishIngredients[array_rand($dishIngredients)];

        return $randomAdjective . ' ' . $randomType . ' ' . $randomIngredient;
    }

    private function generateRandomDrinkName(): string
    {
        $drinkTypes = ['Soda', 'Vine', 'Gin', 'Cocke', 'Cocktail'];
        $drinkAdjectives = ['Extreme', 'Cold', 'Fruity', 'Old fashioned', 'Spicy'];
        $drinkIngredients = ['with lime', 'with lemon', 'with ice', 'virgin', 'dry'];

        $randomType = $drinkTypes[array_rand($drinkTypes)];
        $randomAdjective = $drinkAdjectives[array_rand($drinkAdjectives)];
        $randomIngredient = $drinkIngredients[array_rand($drinkIngredients)];

        return $randomAdjective . ' ' . $randomType . ' ' . $randomIngredient;
    }

}