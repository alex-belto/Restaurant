<?php

namespace App\EventListener\Client;

use App\Entity\Client;
use App\Services\Client\ClientManager;
use App\Services\Restaurant\BuildRestaurant;

class ClientListener
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * @var BuildRestaurant
     */
    private $buildRestaurant;

    /**
     * @param ClientManager $clientManager
     * @param BuildRestaurant $buildRestaurant
     */
    public function __construct(
        ClientManager $clientManager,
        BuildRestaurant $buildRestaurant
    ) {
        $this->clientManager = $clientManager;
        $this->buildRestaurant = $buildRestaurant;
    }

    /**
     * @throws \Exception
     */
    public function postPersist(Client $client) {

        $restaurant = $this->buildRestaurant->getRestaurant();
        $this->clientManager->makeOrder($client, $restaurant);
    }

}