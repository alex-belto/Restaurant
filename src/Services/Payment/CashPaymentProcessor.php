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
    private Payment $processingPayment;

    public function __construct(Payment $processingPayment) {
        $this->processingPayment = $processingPayment;
    }

    public function pay(Client $client, Order $order): void
    {
       $this->processingPayment->payOrder($client, $order);
    }
}