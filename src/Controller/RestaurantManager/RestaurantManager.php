<?php

namespace App\Controller\RestaurantManager;

use App\Repository\ClientRepository;
use App\Repository\OrderRepository;
use App\Repository\RestaurantRepository;
use App\Services\Restaurant\RestaurantBuilder;
use \App\Services\Restaurant\RestaurantManager as Manager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The main controller is responsible for managing the restaurant.
 */
class RestaurantManager extends AbstractController
{
    private Manager $restaurantManager;

    private RestaurantBuilder $restaurantBuilder;

    private ClientRepository $clientRepository;

    private OrderRepository $orderRepository;

    private RestaurantRepository $restaurantRepository;

    /**
     * @param Manager $restaurantManager
     * @param ClientRepository $clientRepository
     * @param RestaurantBuilder $restaurantBuilder
     * @param OrderRepository $orderRepository
     * @param RestaurantRepository $restaurantRepository
     */
    public function __construct(
        Manager                $restaurantManager,
        ClientRepository       $clientRepository,
        RestaurantBuilder      $restaurantBuilder,
        OrderRepository        $orderRepository,
        RestaurantRepository   $restaurantRepository
    ) {
        $this->restaurantManager = $restaurantManager;
        $this->clientRepository = $clientRepository;
        $this->restaurantBuilder = $restaurantBuilder;
        $this->orderRepository = $orderRepository;
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * @throws \Exception
     */
    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): JsonResponse
    {
        set_time_limit(1200);
        $restaurant = $this->restaurantBuilder->getRestaurant($days);

        $result = $this->restaurantManager->startRestaurant($restaurant);
        $this->orderRepository->removeAllOrders();
        $this->clientRepository->removeAllClients();

        return $this->json($result);
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('restaurant/close', name: 'close_restaurant', methods: ['GET'])]
    public function dropRestaurant(): JsonResponse
    {
        $filePath = '/var/www/app/public/restaurant.txt';

        if (file_exists($filePath)) {
            unlink($filePath);
            $message = 'Restaurant closed!';
        } else {
            $message = 'Restaurant not found!';
        }

        $this->restaurantRepository->dropRestaurant();
        return $this->json($message);
    }
}