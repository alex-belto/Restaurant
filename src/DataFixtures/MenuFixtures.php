<?php

namespace App\DataFixtures;

use App\Services\Menu\MenuItemCreator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Creating the main menu.
 */
class MenuFixtures extends Fixture
{
    private  MenuItemCreator $menuItemCreator;

    public function __construct(
        MenuItemCreator $menuItemCreator,
    ) {
        $this->menuItemCreator = $menuItemCreator;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 15; $i++) {
            $dish = $this->menuItemCreator->createDish();
            $manager->persist($dish);
        }

        for ($j = 1; $j <= 5; $j++) {
            $drink = $this->menuItemCreator->createDrink();
            $manager->persist($drink);
        }

        $manager->flush();
    }
}