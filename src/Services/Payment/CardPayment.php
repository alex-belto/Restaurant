<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Interfaces\PaymentInterface;

class CardPayment implements PaymentInterface
{

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        $cardValidation = new CardValidation();

        if ($cardValidation->isCardValid($client)) {
            $processingPayment = new ProcessingPayment();
            $processingPayment($client, $order);

        } else {
            throw new \Exception('Card is not valid!');
        }
    }
}