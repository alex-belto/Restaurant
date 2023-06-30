<?php

namespace App\EventListener\Order;

use App\Entity\Order;
use App\Services\Payment\PaymentManager;

/**
 * Once we receive notification of the order status changing to "DONE," we proceed with processing the payment.
 */
class OrderListener
{
    /**
     * @var PaymentManager
     */
    private $payOrder;

    public function __construct(PaymentManager $payOrder) {
        $this->payOrder = $payOrder;
    }

    public function postUpdate(Order $order) {

        if ($order->getStatus() === Order::DONE) {
            $client = $order->getClient();
            $order->setTips(rand(5, 20));
            $this->payOrder->payOrder($client);
        }
    }

}