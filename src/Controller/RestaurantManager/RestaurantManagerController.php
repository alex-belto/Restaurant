<?php

namespace App\Controller\RestaurantManager;

use App\Repository\RestaurantRepository;
use App\Services\Cleaner\DataCleaner;
use \App\Services\Restaurant\RestaurantManager as Manager;
use App\Services\Restaurant\RestaurantProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The main controller is responsible for managing the restaurant.
 */
class RestaurantManagerController extends AbstractController
{
    private Manager $restaurantManager;
    private RestaurantProvider $restaurantProvider;
    private DataCleaner $dataCleaner;
    private RestaurantRepository $restaurantRepository;

    public function __construct(
        Manager                $restaurantManager,
        RestaurantProvider     $restaurantProvider,
        DataCleaner            $dataCleaner,
        RestaurantRepository   $restaurantRepository
    ) {
        $this->restaurantManager = $restaurantManager;
        $this->restaurantProvider = $restaurantProvider;
        $this->dataCleaner = $dataCleaner;
        $this->restaurantRepository = $restaurantRepository;
    }

    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): JsonResponse
    {
        set_time_limit(1200);
        $restaurant = $this->restaurantProvider->getRestaurant();
        $result = $this->restaurantManager->startRestaurant($restaurant, $days);

        return $this->json($result);
    }

    #[Route('restaurant/close', name: 'close_restaurant', methods: ['GET'])]
    public function dropRestaurant(): JsonResponse
    {
        $message = $this->dataCleaner->removeRestaurantData();

        if ($message === 'Restaurant not found!') {
            return $this->json($message);
        }

        $this->restaurantRepository->dropRestaurant();

        return $this->json($message);
    }
}