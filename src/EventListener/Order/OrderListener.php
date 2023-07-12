<?php

namespace App\EventListener\Order;

use App\Entity\Order;
use App\Services\Payment\PaymentHandler;

/**
 * Once we receive notification of the order status changing to "DONE," we proceed with processing the payment.
 */
class OrderListener
{
    private PaymentHandler $paymentHandler;

    public function __construct(PaymentHandler $paymentHandler) {
        $this->paymentHandler = $paymentHandler;
    }

    public function payOrder(Order $order) {

        if ($order->getStatus() !== Order::DONE) {
            return;
        }

        $client = $order->getClient();
        $order->setTips(rand(0, 20));
        $this->paymentHandler->payOrder($client);
    }

}