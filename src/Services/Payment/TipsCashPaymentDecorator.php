<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\TipsManager;

/**
 * The class is a decorator that adds a tip amount to a cash payment.
 */
class TipsCashPaymentDecorator implements PaymentInterface
{
    /**
     * @var TipsManager
     */
    private $processingTips;

    /**
     * @var CashPaymentProcessor
     */
    private $cashPayment;

    /**
     * @param TipsManager $processingTips
     * @param CashPaymentProcessor $cashPayment
     */
    public function __construct(
        TipsManager $processingTips,
        CashPaymentProcessor $cashPayment
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