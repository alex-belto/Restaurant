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

    public function __construct(
        RestaurantBuilder $restaurantBuilder,
        EntityManagerInterface $em
    ) {
        $this->restaurantBuilder = $restaurantBuilder;
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    public function getRestaurant(?int $days = null): Restaurant
    {
        $filePath = $this->getFilePath();

        if (!file_exists($filePath)) {
            return $this->buildRestaurant($days);
        }

        $restaurantId = file_get_contents($filePath);
        return $this->em->getRepository(Restaurant::class)->find($restaurantId);
    }

    private function buildRestaurant(?int $days = null): Restaurant
    {
        $filePath = $this->getFilePath();
        $restaurant = $this->restaurantBuilder->buildRestaurant($days);
        $this->em->persist($restaurant);
        $this->em->flush();
        file_put_contents($filePath, $restaurant->getId());
        return $restaurant;
    }

    public function getFilePath(): string
    {
        return __DIR__ . $_ENV['FILE_PATH'];
    }
}