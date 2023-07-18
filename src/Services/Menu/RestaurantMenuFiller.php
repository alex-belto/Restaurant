<?php

namespace App\Services\Menu;

use App\Entity\MenuItem;
use App\Entity\Restaurant;
use App\Repository\MenuItemRepository;

class RestaurantMenuFiller
{
    private MenuItemRepository $menuItemRepository;

    public function __construct(MenuItemRepository $menuItemRepository) {
        $this->menuItemRepository = $menuItemRepository;
    }

    /**
     * @throws \Exception
     */
    public function fillUpMenu(Restaurant $restaurant, int $amount, string $type): void
    {
        switch ($type) {
            case 'dish':
                $dish = $this->menuItemRepository->findBy(['type' => MenuItem::DISH]);
                if (count($dish) < $amount) {
                    throw new \Exception('U dont have enough dish in pull');
                }

                for ($i = 0; $i < $amount; $i++) {
                    $restaurant->addMenuItem($dish[$i]);
                }
                break;

            case 'drink':
                $drink = $this->menuItemRepository->findBy(['type' => MenuItem::DRINK]);
                if (count($drink) < $amount) {
                    throw new \Exception('U dont have enough drink in pull');
                }

                for ($i = 0; $i < $amount; $i++) {
                    $restaurant->addMenuItem($drink[$i]);
                }
                break;

            default:
                throw new \Exception('Wrong menuItem type!');
        }
    }
}