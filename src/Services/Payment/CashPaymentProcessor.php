<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;

/**
 * Handles cash payment transactions.
 */
class CashPaymentProcessor implements PaymentInterface
{
    /**
     * @var Payment
     */
    private $processingPayment;

    public function __construct(Payment $processingPayment) {
        $this->processingPayment = $processingPayment;
    }

    /**
     * @param Client $client
     * @param Order $order
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
       $processingPayment = $this->processingPayment;
       $processingPayment($client, $order);
    }
}