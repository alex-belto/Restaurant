<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;

/**
 * The class handles the payment processing for an order.
 */
class Payment
{
    /**
     * @param Client $client
     * @param Order $order
     * @throws \Exception
     */
    public function __invoke(
        Client $client,
        Order $order
    ) {
        $restaurant =  $order->getWaiter()->getRestaurant();
        $restOfMoney = $client->getMoney() - ($order->getPrice() + $order->getTips());
        $client->setMoney($restOfMoney);
        $restaurantBalance = $restaurant->getBalance() + $order->getPrice();
        $restaurant->setBalance($restaurantBalance);
    }

}