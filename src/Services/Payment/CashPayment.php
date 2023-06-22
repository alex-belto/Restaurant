<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;

class CashPayment implements PaymentInterface
{

    public function pay(Client $client, Order $order): void
    {
       $processingPayment = new ProcessingPayment();
       $processingPayment($client, $order);
    }
}