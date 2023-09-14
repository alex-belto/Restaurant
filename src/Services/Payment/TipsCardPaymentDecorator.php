<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\TipsDistributor;

/**
 * Decorator that adds a tip amount to a card payment.
 */
class TipsCardPaymentDecorator implements PaymentInterface
{
    private TipsDistributor $tipsDistributor;

    private CardPaymentProcessor $cardPaymentProcessor;

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
    public function pay(Client $client): void
    {
        $this->cardPaymentProcessor->pay($client);
        $this->tipsDistributor->splitTips($client->getConnectedOrder());
    }
}