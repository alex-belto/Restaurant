<?php

namespace App\Interfaces;

use App\Entity\Client;

interface PaymentInterface
{
    public function pay(Client $client, float $orderValue): void;
}