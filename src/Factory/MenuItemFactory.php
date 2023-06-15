<?php

namespace App\Factory;

use App\Entity\Dish;
use App\Entity\Drink;
use Doctrine\ORM\EntityManagerInterface;

class MenuItemFactory
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createDish(string $name, float $price, string $time): Dish
    {
        $dish = new Dish($name, $price, $time);
        $this->em->persist($dish);
        $this->em->flush();

        return $dish;
    }

    public function createDrink(string $name, float $price, string $time): Drink
    {
        $drink = new Drink($name, $price, $time);
        $this->em->persist($drink);
        $this->em->flush();

        return $drink;
    }
}