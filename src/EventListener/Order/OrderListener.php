<?php

namespace App\EventListener\Order;

use App\Entity\Order;
use App\Services\Payment\PayOrder;

class OrderListener
{
    /**
     * @var PayOrder
     */
    private $payOrder;

    public function __construct(PayOrder $payOrder) {
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