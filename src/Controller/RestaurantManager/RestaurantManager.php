<?php

namespace App\Controller\RestaurantManager;

use \App\Services\Restaurant\RestaurantManager as Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantManager extends AbstractController
{
    /**
     * @var Manager
     */
    private $restaurantManager;

    public function __construct(Manager $restaurantManager) {
        $this->restaurantManager = $restaurantManager;
    }

    /**
     * @throws \Exception
     */
    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): array
    {
        $restaurant = $this->restaurantManager->buildRestaurant();
        $result = $this->restaurantManager->startRestaurant($restaurant, $days);
        return $result;
    }
}