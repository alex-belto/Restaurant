<?php

namespace App\Controller\RestaurantManager;

use App\Entity\Restaurant;
use App\Repository\ClientRepository;
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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param Manager $restaurantManager
     * @param ClientRepository $clientRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        Manager $restaurantManager,
        ClientRepository $clientRepository,
        EntityManagerInterface $em
    ) {
        $this->restaurantManager = $restaurantManager;
        $this->clientRepository = $clientRepository;
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
        $this->em->flush();
        return $this->json($result);
    }

    #[Route('restaurant/stop', name: 'close_restaurant', methods: ['GET'])]
    public function dropRestaurant(): JsonResponse
    {
        $restaurant = Restaurant::getInstance();
        $restaurant->setDays(0);
        $this->em->flush();

        return $this->json(['Restaurant closed!']);
    }
}