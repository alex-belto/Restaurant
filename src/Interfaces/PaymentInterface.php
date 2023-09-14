<?php

namespace App\Interfaces;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Restaurant;

/**
 * Provides a blueprint for payment-related classes, defining methods to process payments.
 */
interface PaymentInterface
{
    public function pay(Client $client): void;
}