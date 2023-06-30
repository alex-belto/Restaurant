<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\ProcessingTips;

class TipsCardPayment implements PaymentInterface
{
    /**
     * @var ProcessingTips
     */
    private $processingTips;

    /**
     * @var CardPayment
     */
    private $cardPayment;

    /**
     * @param ProcessingTips $processingTips
     * @param CardPayment $cardPayment
     */
    public function __construct(
        ProcessingTips $processingTips,
        CardPayment $cardPayment
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