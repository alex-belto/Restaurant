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
    private $cardPayment;

    /**
     * @param TipsDistributor $tipsDistributor
     * @param CardPaymentProcessor $cardPayment
     */
    public function __construct(
        TipsDistributor      $tipsDistributor,
        CardPaymentProcessor $cardPayment
    ) {
        $this->tipsDistributor = $tipsDistributor;
        $this->cardPayment = $cardPayment;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        $this->cardPayment->pay($client, $order);
        $this->tipsDistributor->splitTips($order);
    }
}