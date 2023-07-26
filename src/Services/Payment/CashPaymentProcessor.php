<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles cash payment transactions.
 */
class CashPaymentProcessor implements PaymentInterface
{
    private Payment $processingPayment;
    private EntityManagerInterface $em;

    public function __construct(
        Payment $processingPayment,
        EntityManagerInterface $em
    ) {
        $this->processingPayment = $processingPayment;
        $this->em = $em;
    }

    public function pay(Client $client, Order $order): void
    {
        try {
            $this->em->getConnection()->beginTransaction();
            $this->processingPayment->payOrder($client, $order);
            $client->setStatus(Client::ORDER_PAYED);
            $this->em->flush();
            $this->em->getConnection()->commit();

        } catch (Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }

    }
}