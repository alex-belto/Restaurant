<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;

class CardPayment implements PaymentInterface
{
    /**
     * @var ProcessingPayment
     */
    private $processingPayment;

    /**
     * @var CardValidation
     */
    private $cardValidation;

    /**
     * @var CashPayment
     */
    private $cashPayment;

    /**
     * @param ProcessingPayment $processingPayment
     * @param CardValidation $cardValidation
     * @param CashPayment $cashPayment
     */
    public function __construct(
        ProcessingPayment $processingPayment,
        CardValidation $cardValidation,
        CashPayment $cashPayment
    ) {
        $this->processingPayment = $processingPayment;
        $this->cardValidation = $cardValidation;
        $this->cashPayment = $cashPayment;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        if ($this->cardValidation->isCardValid($client)) {
            $processingPayment = $this->processingPayment;
            $processingPayment($client, $order);
        } else {
            $this->cashPayment->pay($client, $order);
        }
    }
}