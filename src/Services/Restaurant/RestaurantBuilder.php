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
    }

    public function getRestaurant(): Restaurant
    {
        return $this->restaurant;
    }

    public function build(): self
    {
        $this->restaurant = new Restaurant();

        return $this;
    }

    public function hireWaiters(int $amount): self
    {
        $waiters = $this->em->getRepository(Waiter::class)->findAll();
        if (count($waiters) < $amount) {
            throw new \Exception('You dont have enough staff in pull');
        }

        for ($i = 0; $i < $amount; $i++) {
            $this->restaurant->addWaiter($waiters[$i]);
        }

        return $this;
    }

    public function hireKitcheners(int $amount): self
    {
        $kitcheners = $this->em->getRepository(Kitchener::class)->findAll();
        if (count($kitcheners) < $amount) {
            throw new \Exception('You dont have enough staff in pull');
        }

        for ($i = 0; $i < $amount; $i++) {
            $this->restaurant->addKitchener($kitcheners[$i]);
        }

        return $this;
    }

    public function fillUpMenu(int $amount, string $type): self
    {
        switch ($type) {
            case 'dish':
                $dishes = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItemType::DISH]);
                if (count($dishes) < $amount) {
                    throw new \Exception('You dont have enough dish in pull');
                }

                for ($i = 0; $i < $amount; $i++) {
                    $this->restaurant->addMenuItem($dishes[$i]);
                }
                break;

            case 'drink':
                $drinks = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItemType::DRINK]);
                if (count($drinks) < $amount) {
                    throw new \Exception('You dont have enough drink in pull');
                }

                for ($i = 0; $i < $amount; $i++) {
                    $this->restaurant->addMenuItem($drinks[$i]);
                }
                break;

            default:
                throw new \Exception('Wrong menuItem type!');
        }

        return $this;
    }
}