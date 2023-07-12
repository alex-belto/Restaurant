<?php

namespace App\Services\Restaurant;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;

class RestaurantProvider
{
    private RestaurantRepository $restaurantRepository;
    private RestaurantBuilder $restaurantBuilder;
    private EntityManagerInterface $em;
    private string $filePath;

    public function __construct(
        RestaurantRepository $restaurantRepository,
        RestaurantBuilder $restaurantBuilder,
        EntityManagerInterface $em
    ) {
        $this->restaurantRepository = $restaurantRepository;
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
        return $this->restaurantRepository->find($restaurantId);
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