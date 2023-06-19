<?php

namespace App\Controller\RestaurantManager;

use App\Services\Client\ClientManager;
use App\Services\Waiter\WaiterOrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantManager extends AbstractController
{
    /**
     * @var WaiterOrderManager
     */
    private $waiterOrderManager;

    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * @param WaiterOrderManager $waiterOrderManager
     * @param ClientManager $clientManager
     */
    public function __construct(
        WaiterOrderManager $waiterOrderManager,
        ClientManager $clientManager
    ) {
        $this->waiterOrderManager = $waiterOrderManager;
        $this->clientManager = $clientManager;
    }

    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): void
    {
        $client = $this->clientManager->addClient(true);
        $this->waiterOrderManager->processingOrder($client);
    }
}