<?php

namespace App\EventListener\Order;

use App\Entity\Order;
use App\Enum\OrderStatus;
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

    public function payOrder(Order $order): void
    {
        if ($order->getStatus() !== OrderStatus::DONE->getIndex()) {
            return;
        }
        $client = $order->getClient();
        $order->setTips(rand(0, 20));
        $this->paymentHandler->payOrder($client);
    }

}