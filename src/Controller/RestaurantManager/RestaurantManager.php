<?php

namespace App\Controller\RestaurantManager;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\RestaurantRepository;
use \App\Services\Restaurant\RestaurantManager as Manager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantManager extends AbstractController
{
    /**
     * @var Manager
     */
    private $restaurantManager;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var RestaurantRepository
     */
    private $restaurantRepository;

    /**
     * @param Manager $restaurantManager
     * @param ClientRepository $clientRepository
     * @param RestaurantRepository $restaurantRepository
     */
    public function __construct(
        Manager $restaurantManager,
        ClientRepository $clientRepository,
        RestaurantRepository $restaurantRepository
    ) {
        $this->restaurantManager = $restaurantManager;
        $this->clientRepository = $clientRepository;
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * @throws \Exception
     */
    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): array
    {
        $restaurant = $this->restaurantManager->buildRestaurant();
        $result = $this->restaurantManager->startRestaurant($restaurant, $days);
        $this->clientRepository->dropClients();
        $this->restaurantRepository->dropRestaurant();
        return $result;
    }
}