<?php

namespace App\Controller\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardPaymentController extends AbstractController implements PaymentInterface
{

    /**
     * @throws \Exception
     */
    public function pay(Client $client, float $orderValue): void
    {

        if ($this->isValidCard()) {
            $restOfMoney = $client->getMoney() - $orderValue;
            $client->setMoney($restOfMoney);
        } else {
            throw new \Exception('Card is not valid!');
        }
    }

    public function isValidCard(): bool
    {
        //check card
        return true;
    }
}