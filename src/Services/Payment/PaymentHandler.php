<?php

namespace App\Services\Payment;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Selects a random payment method and processes the payment for an order.
 */
class PaymentHandler
{
    private EntityManagerInterface $em;

    private CashPaymentProcessor $cashPaymentProcessor;

    private CardPaymentProcessor $cardPaymentProcessor;

    private TipsCashPaymentDecorator $tipsCashPayment;

    private TipsCardPaymentDecorator $tipsCardPayment;

    private OrderValue $orderValue;

    /**
     * @param EntityManagerInterface $em
     * @param CashPaymentProcessor $cashPaymentProcessor
     * @param CardPaymentProcessor $cardPaymentProcessor
     * @param TipsCashPaymentDecorator $tipsCashPayment
     * @param TipsCardPaymentDecorator $tipsCardPayment
     * @param OrderValue $orderValue
     */
    public function __construct(
        EntityManagerInterface   $em,
        CashPaymentProcessor     $cashPaymentProcessor,
        CardPaymentProcessor     $cardPaymentProcessor,
        TipsCashPaymentDecorator $tipsCashPayment,
        TipsCardPaymentDecorator $tipsCardPayment,
        OrderValue               $orderValue
    ) {
        $this->em = $em;
        $this->cashPaymentProcessor = $cashPaymentProcessor;
        $this->cardPaymentProcessor = $cardPaymentProcessor;
        $this->tipsCashPayment = $tipsCashPayment;
        $this->tipsCardPayment = $tipsCardPayment;
        $this->orderValue = $orderValue;
    }

    /**
     * @throws Exception
     */
    public function payOrder(Client $client): void
    {
        $orderValue = $this->orderValue->getOrderValue($client);
        $payment = $this->getPaymentMethod();

        switch ($payment['paymentStrategy']) {
            case 'cash':
                $paymentStrategy = $this->cashPaymentProcessor;
                $isEnoughMoney = $this->orderValue->isEnoughMoney($client);
                break;
            case 'card':
                $paymentStrategy = $this->cardPaymentProcessor;
                $isEnoughMoney = $this->orderValue->isEnoughMoney($client);
                break;
            case 'cash_tips':
                $paymentStrategy = $this->tipsCashPayment;
                $isEnoughMoney = $this->orderValue->isEnoughMoney($client, $orderValue);
                break;
            case 'card_tips':
                $paymentStrategy = $this->tipsCardPayment;
                $isEnoughMoney = $this->orderValue->isEnoughMoney($client, $orderValue);
                break;
            default:
                throw new Exception('wrong payment strategy');
        }

        try {
            if ($isEnoughMoney) {
                $paymentStrategy->pay($client, $client->getConnectedOrder());
                $client->setStatus(Client::ORDER_PAYED);
                $this->em->flush();
            }
        } catch(\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getPaymentMethod(): array
    {
        $strategyNumber = rand(1,4);

        $paymentStrategy = match ($strategyNumber) {
            1 => 'card',
            2 => 'cash',
            3 => 'cash_tips',
            4 => 'card_tips'
        };

        return [
            'paymentStrategy' => $paymentStrategy
        ];

    }
}