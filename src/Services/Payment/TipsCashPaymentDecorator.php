<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\TipsDistributor;

/**
 * Decorator that adds a tip amount to a cash payment.
 */
class TipsCashPaymentDecorator implements PaymentInterface
{
    private TipsDistributor $tipsDistributor;
    private CashPaymentProcessor $cashPaymentProcessor;

    public function __construct(
        TipsDistributor      $tipsDistributor,
        CashPaymentProcessor $cashPaymentProcessor
    ) {
        $this->tipsDistributor = $tipsDistributor;
        $this->cashPaymentProcessor = $cashPaymentProcessor;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client): void
    {
        $this->cashPaymentProcessor->pay($client);
        $this->tipsDistributor->splitTips($client->getConnectedOrder());
    }
}