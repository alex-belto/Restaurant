<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Exception\CardValidationException;
use App\Interfaces\PaymentInterface;
use Exception;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles card payment transactions.
 * Use a transaction solely for practice purposes.
 */
class CardPaymentProcessor implements PaymentInterface
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function pay(Client $client): void
    {
        if (!$client->isCardValid()) {
            throw new CardValidationException('Card not valid!');
        }

        try {
            $this->em->getConnection()->beginTransaction();

            $client->payOrder();
            $this->em->flush();
            $this->em->getConnection()->commit();

        } catch (Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }

    }
}