<?php

namespace App\Services\Restaurant;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;

class RestaurantProvider
{
    private RestaurantRepository $restaurantRepository;
    private RestaurantBuilder $restaurantBuilder;
    private string $filePath;

    public function __construct(
        RestaurantRepository $restaurantRepository,
        RestaurantBuilder $restaurantBuilder
    ) {
        $this->restaurantRepository = $restaurantRepository;
        $this->restaurantBuilder = $restaurantBuilder;
        $this->filePath = realpath(__DIR__ . '/../../..') . $_ENV['FILE_PATH'];
    }

    /**
     * @throws \Exception
     */
    public function getRestaurant(?int $days = null): Restaurant
    {
        if (!file_exists($this->filePath)) {
            $this->restaurantBuilder->buildRestaurant($days);
        }

        $restaurantId = file_get_contents($this->filePath);
        return $this->restaurantRepository->find($restaurantId);
    }
}