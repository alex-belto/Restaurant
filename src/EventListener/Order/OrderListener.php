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
    private $paymentManager;

    public function __construct(PaymentManager $paymentManager) {
        $this->paymentManager = $paymentManager;
    }

    public function payOrder(Order $order) {

        if ($order->getStatus() === Order::DONE) {
            $client = $order->getClient();
            $order->setTips(rand(0, 20));
            $this->paymentManager->payOrder($client);
        }
    }

}