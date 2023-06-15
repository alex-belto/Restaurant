<?php

namespace App\Interfaces;

use App\Entity\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

interface PaymentInterface
{
    public function pay(Client $client): JsonResponse;
}