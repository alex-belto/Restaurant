<?php

namespace App\Controller\RestaurantManager;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\OrderRepository;
use App\Repository\RestaurantRepository;
use App\Services\Restaurant\BuildRestaurant;
use \App\Services\Restaurant\RestaurantManager as Manager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantManager extends AbstractController
{
    /**
     * @var Manager
     */
    private $restaurantManager;

    /**
     * @var BuildRestaurant
     */
    private $buildRestaurant;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var RestaurantRepository
     */
    private $restaurantRepository;

    /**
     * @param Manager $restaurantManager
     * @param ClientRepository $clientRepository
     * @param EntityManagerInterface $em
     * @param BuildRestaurant $buildRestaurant
     * @param OrderRepository $orderRepository
     * @param RestaurantRepository $restaurantRepository
     */
    public function __construct(
        Manager $restaurantManager,
        ClientRepository $clientRepository,
        EntityManagerInterface $em,
        BuildRestaurant $buildRestaurant,
        OrderRepository $orderRepository,
        RestaurantRepository $restaurantRepository
    ) {
        $this->restaurantManager = $restaurantManager;
        $this->clientRepository = $clientRepository;
        $this->em = $em;
        $this->buildRestaurant = $buildRestaurant;
        $this->orderRepository = $orderRepository;
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * @throws \Exception
     */
    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): JsonResponse
    {
        set_time_limit(360);
        $restaurant = $this->buildRestaurant->getRestaurant();
        $restaurant->setDays($days);
        $this->em->flush();

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
            $message = "File not found!";
        }

        $this->restaurantRepository->dropRestaurant();
        return $this->json($message);
    }
}