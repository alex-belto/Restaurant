<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Exception\CardValidationException;
use Exception;
use Psr\Container\ContainerInterface;

/**
 * Selects a random payment method and processes the payment for an order.
 */
class PaymentHandler
{
    private ContainerInterface $paymentStrategies;

    public function __construct(
        ContainerInterface $paymentStrategies
    ) {
        $this->paymentStrategies = $paymentStrategies;
    }

    /**
     * @throws Exception
     */
    public function payOrder(Client $client): void
    {
        if ($client->getStatus() === Client::ORDER_PAYED) {
            return;
        }

        $restaurant = $client->getRestaurant();

        $paymentStrategy = $this->paymentStrategies->get($client->getPaymentMethod());

        if (!$client->isEnoughMoney()) {
            throw new Exception('Client dont have enough money!');
        }

        try {
            $paymentStrategy->pay($client, $client->getConnectedOrder());
        } catch (CardValidationException $e) {
            $cashPaymentProcessor = $this->paymentStrategies->get($restaurant->getPaymentMethod());
            $cashPaymentProcessor->pay($client, $client->getConnectedOrder());
        }

    }
}