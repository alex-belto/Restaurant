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
    private string $filePath;

    public function __construct(
        RestaurantBuilder $restaurantBuilder,
        EntityManagerInterface $em
    ) {
        $this->restaurantBuilder = $restaurantBuilder;
        $this->em = $em;
        $this->filePath = realpath(__DIR__ . '/../../..') . $_ENV['FILE_PATH'];
    }

    /**
     * @throws \Exception
     */
    public function getRestaurant(?int $days = null): Restaurant
    {
        if (!file_exists($this->filePath)) {
            return $this->buildRestaurant($days);
        }

        $restaurantId = file_get_contents($this->filePath);
        return $this->em->getRepository(Restaurant::class)->find($restaurantId);
    }

    private function buildRestaurant(?int $days = null): Restaurant
    {
        $restaurant = $this->restaurantBuilder->buildRestaurant($days);
        $this->em->persist($restaurant);
        $this->em->flush();
        file_put_contents($this->filePath, $restaurant->getId());
        return $restaurant;
    }
}