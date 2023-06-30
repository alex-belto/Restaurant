<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\ProcessingTips;

class TipsCashPayment implements PaymentInterface
{
    /**
     * @var ProcessingTips
     */
    private $processingTips;

    /**
     * @var CashPayment
     */
    private $cashPayment;

    /**
     * @param ProcessingTips $processingTips
     * @param CashPayment $cashPayment
     */
    public function __construct(
        ProcessingTips $processingTips,
        CashPayment $cashPayment
    ) {
        $this->processingTips = $processingTips;
        $this->cashPayment = $cashPayment;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        $this->cashPayment->pay($client, $order);
        $processingTips = $this->processingTips;
        $processingTips($order);
    }
}