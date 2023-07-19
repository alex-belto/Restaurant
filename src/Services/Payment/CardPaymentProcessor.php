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
    private CardValidation $cardValidation;
    private CashPaymentProcessor $cashPaymentProcessor;
    private EntityManagerInterface $em;

    public function __construct(
        Payment        $processingPayment,
        CardValidation $cardValidation,
        CashPaymentProcessor $cashPaymentProcessor,
        EntityManagerInterface $em
    ) {
        $this->processingPayment = $processingPayment;
        $this->cardValidation = $cardValidation;
        $this->cashPaymentProcessor = $cashPaymentProcessor;
        $this->em = $em;
    }

    public function pay(Client $client, Order $order): void
    {
        $this->em->getConnection()->beginTransaction();

        try {

            if (!$this->cardValidation->isCardValid($client)) {
                throw new CardValidationException('Card not valid!');
            }

            if (!$client->isEnoughMoney()) {
                throw new Exception('Client dont have enough money!');
            }

            $this->processingPayment->payOrder($client, $order);
            $client->setStatus(Client::ORDER_PAYED);
            $this->em->flush();
            $this->em->getConnection()->commit();

        } catch (CardValidationException $e) {
            $this->cashPaymentProcessor->pay($client, $order);
        } catch (Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }

    }
}