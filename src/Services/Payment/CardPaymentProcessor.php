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
    /**
     * @var Payment
     */
    private $processingPayment;

    /**
     * @var CardValidation
     */
    private $cardValidation;

    /**
     * @var CashPaymentProcessor
     */
    private $cashPaymentProcessor;

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
        if ($this->cardValidation->isCardValid($client)) {
           $this->processingPayment->payOrder($client, $order);
        } else {
            $this->cashPaymentProcessor->pay($client, $order);
        }
    }
}