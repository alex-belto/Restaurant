<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Exception\CardValidationException;
use Exception;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Selects a random payment method and processes the payment for an order.
 */
class PaymentHandler
{
    private ServiceLocator $paymentStrategies;

    public function __construct(
        ServiceLocator $paymentStrategies
    ) {
        $this->paymentStrategies = $paymentStrategies;
    }

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
            $paymentStrategy->pay($client);
        } catch (CardValidationException $e) {
            $cashPaymentProcessor = $this->paymentStrategies->get($restaurant->getPaymentMethod());
            $cashPaymentProcessor->pay($client);
        }
    }
}