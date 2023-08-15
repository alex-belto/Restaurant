<?php

namespace App\DataFixtures;

use App\Services\Menu\MenuItemCreator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Creating the main menu.
 */
class MenuFixtures extends Fixture
{
    private  MenuItemCreator $menuItemCreator;
    private EntityManagerInterface $em;

    public function __construct(
        MenuItemCreator $menuItemCreator,
        EntityManagerInterface $em
    )
    {
        $this->menuItemCreator = $menuItemCreator;
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 15; $i++) {
            $dish = $this->menuItemCreator->createDish();
            $this->em->persist($dish);
        }

        for ($j = 1; $j <= 5; $j++) {
            $drink = $this->menuItemCreator->createDrink();
            $this->em->persist($drink);
        }

        $this->em->flush();
    }
}