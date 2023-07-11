<?php

namespace App\EventListener\Client;

use App\Entity\Client;
use App\Services\Client\ClientManager;
use App\Services\Restaurant\RestaurantProvider;

/**
 * After the client is persisted in the system, we proceed to create an order for them.
 */
class ClientListener
{
    private ClientManager $clientManager;

    private RestaurantProvider $restaurantProvider;

    /**
     * @param ClientManager $clientManager
     * @param RestaurantProvider $restaurantProvider
     */
    public function __construct(
        ClientManager $clientManager,
        RestaurantProvider $restaurantProvider
    ) {
        $this->clientManager = $clientManager;
        $this->restaurantProvider = $restaurantProvider;
    }

    /**
     * @throws \Exception
     */
    public function makeOrder(Client $client) {

        $restaurant = $this->restaurantProvider->getRestaurant();
        $this->clientManager->makeOrder($client, $restaurant);
    }

}