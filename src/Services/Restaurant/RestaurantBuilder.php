<?php

namespace App\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\MenuItem;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Constructs a restaurant by hiring staff,
 * creating a menu for the restaurant, and retrieving the restaurant object.
 */
class RestaurantBuilder
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RestaurantRepository
     */
    private $restaurantRepository;

    /**
     * @var int
     */
    private $filePath;

    /**
     * @param EntityManagerInterface $em
     * @param RestaurantRepository $restaurantRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        RestaurantRepository $restaurantRepository
    ) {
        $this->em = $em;
        $this->restaurantRepository = $restaurantRepository;
        $this->filePath = '/var/www/app/public/restaurant.txt';
    }

    /**
     * @throws \Exception
     */
    public function buildRestaurant(int $days): Restaurant
    {

        $restaurant = new Restaurant();

        $this->hireKitcheners($restaurant, 3);
        $this->hireWaiters($restaurant, 7);
        $this->fillUpMenu($restaurant, 15, 'dish');
        $this->fillUpMenu($restaurant,  4, 'drink');
        $restaurant->setDays($days);
        $this->em->persist($restaurant);
        $this->em->flush();
        file_put_contents($this->filePath, $restaurant->getId());

        return $restaurant;
    }

    public function getRestaurant(?int $days = null): Restaurant
    {
        if (!file_exists($this->filePath)) {
            $this->buildRestaurant($days);
        }

        $restaurantId = file_get_contents($this->filePath);
        return $this->restaurantRepository->find($restaurantId);
    }

    /**
     * @param Restaurant $restaurant
     * @param int $amount
     * @throws \Exception
     */
    public function hireWaiters(Restaurant $restaurant, int $amount): void
    {
        $waiters = $this->em->getRepository(Waiter::class)->findAll();
        if (count($waiters) >= $amount) {
            for ($i = 0; $i < $amount; $i++) {
                $restaurant->addWaiter($waiters[$i]);
            }
        } else {
            throw new \Exception('U dont have enough staff in pull');
        }
    }

    /**
     * @param Restaurant $restaurant
     * @param int $amount
     * @throws \Exception
     */
    public function hireKitcheners(Restaurant $restaurant, int $amount): void
    {
        $kitcheners = $this->em->getRepository(Kitchener::class)->findAll();
        if (count($kitcheners) >= $amount) {
            for ($i = 0; $i < $amount; $i++) {
                $restaurant->addKitchener($kitcheners[$i]);
            }
        } else {
            throw new \Exception('U dont have enough staff in pull');
        }
    }

    /**
     * @throws \Exception
     */
    public function fillUpMenu(Restaurant $restaurant, int $amount, string $type): void
    {
        switch ($type) {
            case 'dish':
                $dish = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItem::DISH]);
                if (count($dish) >= $amount) {
                    for ($i = 0; $i < $amount; $i++) {
                        $restaurant->addMenuItem($dish[$i]);
                    }
                } else {
                    throw new \Exception('U dont have enough dish in pull');
                }
                break;
            case 'drink':
                $drink = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItem::DRINK]);
                if (count($drink) >= $amount) {
                    for ($i = 0; $i < $amount; $i++) {
                        $restaurant->addMenuItem($drink[$i]);
                    }
                } else {
                    throw new \Exception('U dont have enough drink in pull');
                }
                break;
            default:
                throw new \Exception('Wrong menuItem type!');
        }
    }

}