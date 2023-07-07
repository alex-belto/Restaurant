<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\TipsDistributor;

/**
 * Decorator that adds a tip amount to a card payment.
 */
class TipsCardPaymentDecorator implements PaymentInterface
{
    /**
     * @var TipsDistributor
     */
    private $tipsDistributor;

    /**
     * @var CardPaymentProcessor
     */
    private $cardPaymentProcessor;

    /**
     * @param TipsDistributor $tipsDistributor
     * @param CardPaymentProcessor $cardPaymentProcessor
     */
    public function __construct(
        TipsDistributor      $tipsDistributor,
        CardPaymentProcessor $cardPaymentProcessor
    ) {
        $this->tipsDistributor = $tipsDistributor;
        $this->cardPaymentProcessor = $cardPaymentProcessor;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        $this->cardPaymentProcessor->pay($client, $order);
        $this->tipsDistributor->splitTips($order);
    }
}