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
    private ClientManager $clientManager;

    private RestaurantBuilder $restaurantBuilder;

    /**
     * @param ClientManager $clientManager
     * @param RestaurantBuilder $restaurantBuilder
     */
    public function __construct(
        ClientManager $clientManager,
        RestaurantBuilder $restaurantBuilder
    ) {
        $this->clientManager = $clientManager;
        $this->restaurantBuilder = $restaurantBuilder;
    }

    /**
     * @throws \Exception
     */
    public function makeOrder(Client $client) {

        $restaurant = $this->restaurantBuilder->getRestaurant();
        $this->clientManager->makeOrder($client, $restaurant);
    }

}