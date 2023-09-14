<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Enum\ClientStatus;
use App\Enum\OrderStatus;
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
        $paymentStrategy = $this->paymentStrategies->get($client->getPaymentMethod());

        if (!$client->isEnoughMoneyForOrder()) {
            throw new Exception('Client dont have enough money!');
        }

        try {
            $paymentStrategy->pay($client);
        } catch (CardValidationException $e) {
            $restaurant = $client->getRestaurant();
            $cashPaymentProcessor = $this->paymentStrategies->get($restaurant->getPaymentMethod());
            $cashPaymentProcessor->pay($client);
        }
    }
}