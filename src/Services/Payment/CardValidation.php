<?php

namespace App\Services\Payment;

use App\Entity\Client;
use DateTime;

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