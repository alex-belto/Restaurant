<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Exception\CardValidationException;
use App\Interfaces\PaymentInterface;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Selects a random payment method and processes the payment for an order.
 */
class PaymentHandler
{
    private ContainerInterface $container;

    public function __construct(
        ContainerInterface       $container
    ) {
        $this->container = $container;
    }

    /**
     * @throws Exception
     */
    public function payOrder(Client $client): void
    {
        $payment = $this->getPaymentMethod();
        $paymentStrategy = match ($payment['paymentStrategy']) {
            'cash' => $this->container->get('App\Services\Payment\CashPaymentProcessor'),
            'card' => $this->container->get('App\Services\Payment\CardPaymentProcessor'),
            'cash_tips' => $this->container->get('App\Services\Payment\TipsCashPaymentDecorator'),
            'card_tips' => $this->container->get('App\Services\Payment\TipsCardPaymentDecorator'),
            default => throw new Exception('wrong payment strategy'),
        };

        if (!$client->isEnoughMoney()) {
            throw new \Doctrine\DBAL\Exception('Client dont have enough money!');
        }

        try {
            /** @var PaymentInterface $paymentStrategy */
            $paymentStrategy->pay($client, $client->getConnectedOrder());
        } catch (CardValidationException $e) {
            $cashPaymentProcessor = $this->container->get('App\Services\Payment\CashPaymentProcessor');
            $cashPaymentProcessor->pay($client, $client->getConnectedOrder());
        }

    }

    private function getPaymentMethod(): array
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