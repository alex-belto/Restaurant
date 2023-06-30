<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\TipsManager;

/**
 * The class is a decorator that adds a tip amount to a card payment.
 */
class TipsCardPaymentDecorator implements PaymentInterface
{
    /**
     * @var TipsManager
     */
    private $processingTips;

    /**
     * @var CardPaymentProcessor
     */
    private $cardPayment;

    /**
     * @param TipsManager $processingTips
     * @param CardPaymentProcessor $cardPayment
     */
    public function __construct(
        TipsManager $processingTips,
        CardPaymentProcessor $cardPayment
    ) {
        $this->processingTips = $processingTips;
        $this->cardPayment = $cardPayment;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        $this->cardPayment->pay($client, $order);
        $processingTips = $this->processingTips;
        $processingTips($order);
    }
}