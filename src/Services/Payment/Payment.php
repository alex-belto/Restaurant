<?php

namespace App\Services\Payment;

use App\Entity\Client;

/**
 * Handles the payment processing for an order.
 */
class Payment
{
    public function payOrder(Client $client): void
    {
        $order = $client->getConnectedOrder();
        $restaurant =  $client->getRestaurant();
        $restOfMoney = $client->getMoney() - ($order->getPrice() + $order->getTips());
        $client->setMoney($restOfMoney);
        $restaurantBalance = $restaurant->getBalance() + $order->getPrice();
        $restaurant->setBalance($restaurantBalance);
    }
}