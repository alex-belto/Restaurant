<?php

namespace App\Interfaces;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Restaurant;

/**
 * The Payment interface provides a blueprint for payment-related classes, defining methods to process payments.
 */
interface PaymentInterface
{
    public function pay(Client $client, Order $order): void;
}