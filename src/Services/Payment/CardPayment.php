<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;

class CardPayment implements PaymentInterface
{

    /**
     * @throws \Exception
     */
    public function pay(Client $client, float $orderValue): void
    {
        $cardValidation = new CardValidation();

        if ($cardValidation->isCardValid($client)) {
            $restOfMoney = $client->getMoney() - $orderValue;
            $client->setMoney($restOfMoney);
        } else {
            throw new \Exception('Card is not valid!');
        }
    }
}