<?php

namespace App\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\MenuItem;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Constructs a restaurant by hiring staff,
 * creating a menu for the restaurant, and retrieving the restaurant object.
 */
class RestaurantBuilder
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    public function buildRestaurant(int $days): Restaurant
    {
        $restaurant = $this->reset();
        $this->hireKitcheners($restaurant, 3);
        $this->hireWaiters($restaurant, 7);
        $this->fillUpMenu($restaurant, 15, 'dish');
        $this->fillUpMenu($restaurant,  4, 'drink');
        $restaurant->setDays($days);

        return $restaurant;
    }

    public function reset(): Restaurant
    {
        return new Restaurant();
    }

    public function hireWaiters(Restaurant $restaurant, int $amount): void
    {
        $waiters = $this->em->getRepository(Waiter::class)->findAll();
        if (!count($waiters) >= $amount) {
            throw new \Exception('U dont have enough staff in pull');
        }

        for ($i = 0; $i < $amount; $i++) {
            $restaurant->addWaiter($waiters[$i]);
        }
    }

    public function hireKitcheners(Restaurant $restaurant, int $amount): void
    {
        $kitcheners = $this->em->getRepository(Kitchener::class)->findAll();
        if (!count($kitcheners) >= $amount) {
            throw new \Exception('U dont have enough staff in pull');
        }

        for ($i = 0; $i < $amount; $i++) {
            $restaurant->addKitchener($kitcheners[$i]);
        }
    }

    public function fillUpMenu(Restaurant $restaurant, int $amount, string $type): void
    {
        switch ($type) {
            case 'dish':
                $dish = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItem::DISH]);
                if (count($dish) < $amount) {
                    throw new \Exception('U dont have enough dish in pull');
                }

                for ($i = 0; $i < $amount; $i++) {
                    $restaurant->addMenuItem($dish[$i]);
                }
                break;

            case 'drink':
                $drink = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItem::DRINK]);
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