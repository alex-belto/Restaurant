<?php

namespace App\Controller\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CashPaymentController extends AbstractController implements PaymentInterface
{

    public function pay(Client $client, float $orderValue): void
    {
        $restOfMoney = $client->getMoney() - $orderValue;
        $client->setMoney($restOfMoney);
    }
}