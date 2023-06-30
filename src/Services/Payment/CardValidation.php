<?php

namespace App\Services\Payment;

use App\Entity\Client;
use DateTime;

/**
 * The class checks the validity of a credit or debit card.
 */
class CardValidation
{
    public function isCardValid(Client $client): bool
    {
        $expirationDate = $client->getCardExpirationDate();
        if ($expirationDate < new DateTime()) {
            return false;
        }
        return true;
    }

}