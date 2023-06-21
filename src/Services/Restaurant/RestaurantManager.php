<?php

namespace App\Services\Restaurant;

use App\Entity\Order;
use App\Services\Client\ClientManager;
use App\Services\Payment\PayOrder;

class RestaurantManager
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * @var PayOrder
     */
    private $payOrder;

    /**
     * @param ClientManager $clientManager
     * @param PayOrder $payOrder
     */
    public function __construct(
        ClientManager $clientManager,
        PayOrder $payOrder
    ) {
        $this->clientManager = $clientManager;
        $this->payOrder = $payOrder;
    }

    /**
     * @throws \Exception
     */
    public function startRestaurant()
    {
        $client = $this->clientManager->addClient(true);
        $this->clientManager->makeOrder($client);
        if ($client->getConnectedOrder()->getStatus() === Order::DONE) {
            $this->payOrder->payOrder($client);
        }
    }
}