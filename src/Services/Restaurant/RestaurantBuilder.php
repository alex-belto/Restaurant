<?php

namespace App\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\MenuItem;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Enum\MenuItemType;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Constructs a restaurant by hiring staff,
 * creating a menu for the restaurant, and retrieving the restaurant object.
 */
class RestaurantBuilder
{
    private EntityManagerInterface $em;
    private Restaurant $restaurant;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
        $this->restaurant = new Restaurant();
    }

    /**
     * @throws \Exception
     */
    public function buildRestaurant(int $days): Restaurant
    {
        $restaurant = $this->build();
        $this
            ->hireKitcheners($restaurant, 3)
            ->hireWaiters($restaurant, 7)
            ->fillUpMenu($restaurant, 15, 'dish')
            ->fillUpMenu($restaurant, 4, 'drink');
        $restaurant->setDays($days);

        return $restaurant;
    }

    public function build(): Restaurant
    {
        return $this->restaurant;
    }

    public function hireWaiters(Restaurant $restaurant, int $amount): self
    {
        $waiters = $this->em->getRepository(Waiter::class)->findAll();
        if (count($waiters) < $amount) {
            throw new \Exception('You dont have enough staff in pull');
        }

        for ($i = 0; $i < $amount; $i++) {
            $restaurant->addWaiter($waiters[$i]);
        }

        return $this;
    }

    public function hireKitcheners(Restaurant $restaurant, int $amount): self
    {
        $kitcheners = $this->em->getRepository(Kitchener::class)->findAll();
        if (count($kitcheners) < $amount) {
            throw new \Exception('You dont have enough staff in pull');
        }

        for ($i = 0; $i < $amount; $i++) {
            $restaurant->addKitchener($kitcheners[$i]);
        }

        return $this;
    }

    public function fillUpMenu(Restaurant $restaurant, int $amount, string $type): self
    {
        switch ($type) {
            case 'dish':
                $dishes = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItemType::DISH]);
                if (count($dishes) < $amount) {
                    throw new \Exception('You dont have enough dish in pull');
                }

                for ($i = 0; $i < $amount; $i++) {
                    $restaurant->addMenuItem($dishes[$i]);
                }
                break;

            case 'drink':
                $drinks = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItemType::DRINK]);
                if (count($drinks) < $amount) {
                    throw new \Exception('You dont have enough drink in pull');
                }

                for ($i = 0; $i < $amount; $i++) {
                    $restaurant->addMenuItem($drinks[$i]);
                }
                break;

            default:
                throw new \Exception('Wrong menuItem type!');
        }

        return $this;
    }
}