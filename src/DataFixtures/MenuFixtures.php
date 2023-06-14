<?php

namespace App\DataFixtures;

use App\Factory\MenuItemFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenuFixtures extends Fixture
{
    private MenuItemFactory $menuFactory;

    public function __construct(MenuItemFactory $menuFactory)
    {
        $this->menuFactory = $menuFactory;
    }

    public function load(ObjectManager $manager)
    {
        $this->menuFactory->createDish('Pizza Margarita', 7.5, '0.5h');
        $this->menuFactory->createDish('Pizza Diablo', 8.7, '0.5h');
        $this->menuFactory->createDish('Pizza Hawaii', 8.0, '0.5h');
        $this->menuFactory->createDish('Pizza Peperoni', 8.2, '0.5h');
        $this->menuFactory->createDish('Spaghetti Bolognese', 6.0, '0.5h');
        $this->menuFactory->createDish('Spaghetti Tonne', 6.0, '0.5h');
        $this->menuFactory->createDish('Chicken Soup', 4.3, '0.5h');
        $this->menuFactory->createDish('Chicken Broth', 3.7, '0.5h');
        $this->menuFactory->createDish('Borsch', 8.0, '0.5h');
        $this->menuFactory->createDish('Spinach puree', 7.5, '0.5h');
        $this->menuFactory->createDish('French fries', 4.0, '0.3h');
        $this->menuFactory->createDish('Beef Steak', 10.5, '0.5h');
        $this->menuFactory->createDish('Donner', 6.5, '0.2h');
        $this->menuFactory->createDish('Pancakes', 5.0, '0.5h');
        $this->menuFactory->createDish('Apple Pie', 6.5, '0.1h');

        $this->menuFactory->createDrink('Cola', '1.5', '0.1h');
        $this->menuFactory->createDrink('Spring Water', '1.0', '0.1h');
        $this->menuFactory->createDrink('Coffee', '1.0', '0.1h');
        $this->menuFactory->createDrink('Tea', '1.0', '0.1h');
    }
}