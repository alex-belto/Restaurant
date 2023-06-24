<?php

namespace App\Controller\RestaurantManager;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\RestaurantRepository;
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
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var RestaurantRepository
     */
    private $restaurantRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param Manager $restaurantManager
     * @param ClientRepository $clientRepository
     * @param RestaurantRepository $restaurantRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        Manager $restaurantManager,
        ClientRepository $clientRepository,
        RestaurantRepository $restaurantRepository,
        EntityManagerInterface $em
    ) {
        $this->restaurantManager = $restaurantManager;
        $this->clientRepository = $clientRepository;
        $this->restaurantRepository = $restaurantRepository;
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): JsonResponse
    {
        $restaurant = $this->restaurantManager->buildRestaurant($days);
        $result = $this->restaurantManager->startRestaurant($restaurant);
//        $this->clientRepository->dropClients();
//        $this->em->remove($restaurant);
        $this->em->flush();
        return $this->json($result);
    }
}