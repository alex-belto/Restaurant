<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Restaurant;

class ProcessingPayment
{
    public function __invoke(
        Client $client,
        Order $order
    ) {
        dd($client->getWaiter()->getRestaurant());
        $restaurant =  $client->getWaiter()->getRestaurant();
        $restOfMoney = $client->getMoney() - ($order->getPrice() + $order->getTips());
        $client->setMoney($restOfMoney);
        $restaurantBalance = $restaurant->getBalance() + $order->getPrice();
        $restaurant->setBalance($restaurantBalance);
    }

}