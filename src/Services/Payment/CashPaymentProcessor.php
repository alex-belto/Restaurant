<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Enum\ClientStatus;
use App\Interfaces\PaymentInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles cash payment transactions.
 */
class CashPaymentProcessor implements PaymentInterface
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function pay(Client $client): void
    {
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