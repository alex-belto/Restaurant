<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Selects a random payment method and processes the payment for an order.
 */
class PaymentHandler
{
    private EntityManagerInterface $em;
    private ContainerInterface $container;

    public function __construct(
        EntityManagerInterface   $em,
        ContainerInterface       $container,
    ) {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * @throws Exception
     */
    public function payOrder(Client $client): void
    {
        if ($client->getStatus() === Client::ORDER_PAYED) {
            return;
        }

        $payment = $this->getPaymentMethod();
        $paymentStrategy = match ($payment['paymentStrategy']) {
            'cash' => $this->container->get('App\Services\Payment\CashPaymentProcessor'),
            'card' => $this->container->get('App\Services\Payment\CardPaymentProcessor'),
            'cash_tips' => $this->container->get('App\Services\Payment\TipsCashPaymentDecorator'),
            'card_tips' => $this->container->get('App\Services\Payment\TipsCardPaymentDecorator'),
            default => throw new Exception('wrong payment strategy'),
        };

            /** @var PaymentInterface $paymentStrategy */
            $paymentStrategy->pay($client, $client->getConnectedOrder());

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