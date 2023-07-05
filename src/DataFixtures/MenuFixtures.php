<?php

namespace App\DataFixtures;

use App\Entity\MenuItem;
use App\Services\Menu\MenuItemCreator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Creating the main menu.
 */
class MenuFixtures extends Fixture
{
    private  MenuItemCreator $createMenuItem;

    public function __construct(MenuItemCreator $createMenuItem)
    {
        $this->createMenuItem = $createMenuItem;
    }

    public function load(ObjectManager $manager)
    {
        $this->createMenuItem->createMenuItem('Pizza Margarita', 7.5, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Pizza Diablo', 8.7, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Pizza Hawaii', 8.0, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Pizza Peperoni', 8.2, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Spaghetti Bolognese', 6.0, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Spaghetti Tonne', 6.0, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Chicken Soup', 4.3, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Chicken Broth', 3.7, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Borsch', 8.0, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Spinach puree', 7.5, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('French fries', 4.0, '0.3h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Beef Steak', 10.5, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Donner', 6.5, '0.2h',MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Pancakes', 5.0, '0.5h', MenuItem::DISH);
        $this->createMenuItem->createMenuItem('Apple Pie', 6.5, '0.1h', MenuItem::DISH);

        $this->createMenuItem->createMenuItem('Cola', '1.5', '0.1h', MenuItem::DRINK);
        $this->createMenuItem->createMenuItem('Spring Water', '1.0', '0.1h', MenuItem::DRINK);
        $this->createMenuItem->createMenuItem('Coffee', '1.0', '0.1h', MenuItem::DRINK);
        $this->createMenuItem->createMenuItem('Tea', '1.0', '0.1h', MenuItem::DRINK);
    }
}