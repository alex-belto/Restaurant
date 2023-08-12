<?php

namespace App\Services\Restaurant;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;

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

    public function getRestaurant(?int $days = null): Restaurant
    {
        if (!file_exists($this->filePath)) {
            return $this->buildRestaurant($days);
        }

        $restaurantId = file_get_contents($this->filePath);
        $restaurant = $this->em->getRepository(Restaurant::class)->find($restaurantId);

        if ($restaurant === null) {
            $restaurant = $this->buildRestaurant($days);
        }

        return $restaurant;
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