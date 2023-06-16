<?php

namespace App\Controller\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;

class TipsCashPaymentController implements PaymentInterface
{
    /**
     * @var int
     */
    private int $tipsPercent;

    /**
     * @var CashPaymentController
     */
    private $cashPaymentController;

    /**
     * @param int $tipsPercent
     * @param CashPaymentController $cashPaymentController
     */
    public function __construct(
        int $tipsPercent,
        CashPaymentController $cashPaymentController
    ) {
        $this->tipsPercent = $tipsPercent;
        $this->cashPaymentController = $cashPaymentController;
    }

    public function pay(Client $client, float $orderValue): void
    {
        $tips = $orderValue / 100 * $this->tipsPercent;
        $orderValue += $tips;

       $this->cashPaymentController->pay($client, $orderValue);
    }
}