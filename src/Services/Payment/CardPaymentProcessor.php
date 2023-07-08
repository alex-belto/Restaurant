<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;

/**
 * Handles card payment transactions.
 */
class CardPaymentProcessor implements PaymentInterface
{
    private Payment $processingPayment;

    private CardValidation $cardValidation;

    private CashPaymentProcessor $cashPaymentProcessor;

    /**
     * @param Payment $processingPayment
     * @param CardValidation $cardValidation
     * @param CashPaymentProcessor $cashPaymentProcessor
     */
    public function __construct(
        Payment        $processingPayment,
        CardValidation $cardValidation,
        CashPaymentProcessor $cashPaymentProcessor
    ) {
        $this->processingPayment = $processingPayment;
        $this->cardValidation = $cardValidation;
        $this->cashPaymentProcessor = $cashPaymentProcessor;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        if (!$this->cardValidation->isCardValid($client)) {
            $this->cashPaymentProcessor->pay($client, $order);
        }
        $this->processingPayment->payOrder($client, $order);
    }
}