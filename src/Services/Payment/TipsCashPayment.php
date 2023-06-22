<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\ProcessingTips;

class TipsCashPayment implements PaymentInterface
{
    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        $cashPayment = new CashPayment();
        $cashPayment->pay($client, $order);
        $processingTips = new ProcessingTips();
        $processingTips($order);
    }
}