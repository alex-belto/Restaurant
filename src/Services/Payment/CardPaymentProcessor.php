<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Exception\CardValidationException;
use App\Interfaces\PaymentInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles card payment transactions.
 */
class CardPaymentProcessor implements PaymentInterface
{
    private Payment $processingPayment;
    private EntityManagerInterface $em;

    public function __construct(
        Payment        $processingPayment,
        EntityManagerInterface $em
    ) {
        $this->processingPayment = $processingPayment;
        $this->em = $em;
    }

    public function pay(Client $client, Order $order): void
    {
        if (!$client->isCardValid()) {
            throw new CardValidationException('Card not valid!');
        }

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