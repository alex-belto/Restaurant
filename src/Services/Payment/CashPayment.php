<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;

class CashPayment implements PaymentInterface
{
    /**
     * @var ProcessingPayment
     */
    private $processingPayment;

    public function __construct(ProcessingPayment $processingPayment) {
        $this->processingPayment = $processingPayment;
    }

    /**
     * @param Client $client
     * @param Order $order
     */
    public function pay(Client $client, Order $order): void
    {
       $processingPayment = $this->processingPayment;
       $processingPayment($client, $order);
    }
}