<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Enum\ClientStatus;
use App\Exception\CardValidationException;
use App\Interfaces\PaymentInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles card payment transactions.
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
            $client->setStatus(ClientStatus::ORDER_PAYED->getIndex());
            $this->em->flush();
            $this->em->getConnection()->commit();

        } catch (Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }

    }
}