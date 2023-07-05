<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\TipsDistributor;

/**
 * The class is a decorator that adds a tip amount to a cash payment.
 */
class TipsCashPaymentDecorator implements PaymentInterface
{
    /**
     * @var TipsDistributor
     */
    private $tipsDistributor;

    /**
     * @var CashPaymentProcessor
     */
    private $cashPayment;

    /**
     * @param TipsDistributor $tipsDistributor
     * @param CashPaymentProcessor $cashPayment
     */
    public function __construct(
        TipsDistributor      $tipsDistributor,
        CashPaymentProcessor $cashPayment
    ) {
        $this->tipsDistributor = $tipsDistributor;
        $this->cashPayment = $cashPayment;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        $this->cashPayment->pay($client, $order);
        $tipsDistributor = $this->tipsDistributor;
        $tipsDistributor($order);
    }
}