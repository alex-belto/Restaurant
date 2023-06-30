<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;

/**
 * The class handles card payment transactions.
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
    private $cashPayment;

    /**
     * @param Payment $processingPayment
     * @param CardValidation $cardValidation
     * @param CashPaymentProcessor $cashPayment
     */
    public function __construct(
        Payment        $processingPayment,
        CardValidation $cardValidation,
        CashPaymentProcessor $cashPayment
    ) {
        $this->processingPayment = $processingPayment;
        $this->cardValidation = $cardValidation;
        $this->cashPayment = $cashPayment;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        if ($this->cardValidation->isCardValid($client)) {
            $processingPayment = $this->processingPayment;
            $processingPayment($client, $order);
        } else {
            $this->cashPayment->pay($client, $order);
        }
    }
}