<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;

class TipsCardPayment implements PaymentInterface
{
    /**
     * @var integer
     */
    private int $tipsPercent;

    /**
     * @throws \Exception
     */
    public function pay(Client $client, float $orderValue): void
    {
        $cardPayment = new CardPayment();
        $tips = $orderValue / 100 * $this->tipsPercent;
        $orderValue += $tips;

        $cardPayment->pay($client, $orderValue);
    }

    public function setTips(int $tipsPercent): void
    {
        $this->tipsPercent = $tipsPercent;
    }
}