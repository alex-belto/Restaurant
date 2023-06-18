<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;

class TipsCashPayment implements PaymentInterface
{
    /**
     * @var int
     */
    private int $tipsPercent;

    public function pay(Client $client, float $orderValue): void
    {
        $cashPayment = new CashPayment();
        $tips = $orderValue / 100 * $this->tipsPercent;
        $orderValue += $tips;

       $cashPayment->pay($client, $orderValue);
    }

    public function setTips(int $tipsPercent): void
    {
        $this->tipsPercent = $tipsPercent;
    }
}