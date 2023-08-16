<?php

namespace App\Controller\RestaurantManager;

use App\Repository\RestaurantRepository;
use App\Services\Cleaner\ClientCleaner;
use App\Services\Cleaner\DataCleaner;
use App\Services\Cleaner\OrderCleaner;
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

    /**
     * @throws \Exception
     */
    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): JsonResponse
    {
        set_time_limit(1200);
        $restaurant = $this->restaurantProvider->getRestaurant($days);
        $result = $this->restaurantManager->startRestaurant($restaurant);

        return $this->json($result);
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('restaurant/close', name: 'close_restaurant', methods: ['GET'])]
    public function dropRestaurant(): JsonResponse
    {
        $basePath = realpath(__DIR__ . '/../../..');
        $filePath = $basePath . $_ENV['FILE_PATH'];

        if (file_exists($filePath)) {
            unlink($filePath);
            $this->dataCleaner->removeAllOrders();
            $this->dataCleaner->removeAllClients();
            $message = 'Restaurant closed!';
        } else {
            $message = 'Restaurant not found!';
        }

        $this->restaurantRepository->dropRestaurant();
        return $this->json($message);
    }
}