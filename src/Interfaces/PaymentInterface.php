<?php

namespace App\Interfaces;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Restaurant;

interface PaymentInterface
{
    public function pay(Client $client, Order $order): void;
}