<?php

namespace App\Controller\RestaurantManager;

use App\Services\Client\ClientManager;
use App\Services\Waiter\WaiterManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantManager extends AbstractController
{
    /**
     * @var WaiterManager
     */
    private $waiterOrderManager;

    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * @param WaiterManager $waiterOrderManager
     * @param ClientManager $clientManager
     */
    public function __construct(
        WaiterManager $waiterOrderManager,
        ClientManager $clientManager
    ) {
        $this->waiterOrderManager = $waiterOrderManager;
        $this->clientManager = $clientManager;
    }

    #[Route('/restaurant/open/{days}', name: 'open_restaurant', methods: ['GET'])]
    public function openRestaurant(int $days): void
    {
        $client = $this->clientManager->addClient(true);
        $this->clientManager->makeOrder($client);
    }
}