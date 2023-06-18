<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;

class CashPayment implements PaymentInterface
{

    public function pay(Client $client, float $orderValue): void
    {
        $restOfMoney = $client->getMoney() - $orderValue;
        $client->setMoney($restOfMoney);
    }
}