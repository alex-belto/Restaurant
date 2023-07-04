<?php

namespace App\EventListener\Client;

use App\Entity\Client;
use App\Services\Client\ClientManager;
use App\Services\Restaurant\RestaurantBuilder;

/**
 * After the client is persisted in the system, we proceed to create an order for them.
 */
class ClientListener
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * @var RestaurantBuilder
     */
    private $buildRestaurant;

    /**
     * @param ClientManager $clientManager
     * @param RestaurantBuilder $buildRestaurant
     */
    public function __construct(
        ClientManager $clientManager,
        RestaurantBuilder $buildRestaurant
    ) {
        $this->clientManager = $clientManager;
        $this->buildRestaurant = $buildRestaurant;
    }

    /**
     * @throws \Exception
     */
    public function makeOrder(Client $client) {

        $restaurant = $this->buildRestaurant->getRestaurant();
        $this->clientManager->makeOrder($client, $restaurant);
    }

}