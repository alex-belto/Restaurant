<?php

namespace App\Services\Restaurant;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;

/**
 * A class responsible for supplying a restaurant instance for operations.
 */
class RestaurantProvider
{
    private RestaurantBuilder $restaurantBuilder;
    private EntityManagerInterface $em;
    private string $restaurantFilePath;

    public function __construct(
        RestaurantBuilder $restaurantBuilder,
        EntityManagerInterface $em,
        $restaurantFilePath
    ) {
        $this->restaurantBuilder = $restaurantBuilder;
        $this->em = $em;
        $this->restaurantFilePath = $restaurantFilePath;
    }

    public function getRestaurant(?int $days = null): Restaurant
    {
        $filePath = $this->getRestaurantFilePath();

        if (!file_exists($filePath)) {
            return $this->buildRestaurant($days);
        }

        $restaurantId = file_get_contents($filePath);
        $restaurant = $this->em->getRepository(Restaurant::class)->find($restaurantId);

        if ($restaurant === null) {
            $restaurant = $this->buildRestaurant($days);
        }

        return $restaurant;
    }

    private function buildRestaurant(?int $days = null): Restaurant
    {
        $filePath = $this->getRestaurantFilePath();
        $restaurant = $this->restaurantBuilder
            ->build()
            ->hireKitcheners(3)
            ->hireWaiters(7)
            ->fillUpMenu(15, 'dish')
            ->fillUpMenu(4, 'drink')
            ->getRestaurant();

        $restaurant->setDays($days);
        $this->em->persist($restaurant);
        $this->em->flush();
        file_put_contents($filePath, $restaurant->getId());
        return $restaurant;
    }

    public function getRestaurantFilePath(): string
    {
        return $this->restaurantFilePath;
    }
}