<?php

namespace App\Services\Payment;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PayOrder
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @throws Exception
     */
    public function payOrder(Client $client): void
    {
        $orderValueClass = new OrderValue();
        $orderValue = $orderValueClass->getOrderValue($client);
        $payment = $this->getPaymentMethod();

        switch ($payment['paymentStrategy']) {
            case 'cash':
                $paymentStrategy = new CashPayment();
                $isEnoughMoney = $orderValueClass->isEnoughMoney($client);
                break;
            case 'card':
                $paymentStrategy = new CardPayment();
                $isEnoughMoney = $orderValueClass->isEnoughMoney($client);
                break;
            case 'cash_tips':
                $paymentStrategy = new TipsCardPayment();
                $isEnoughMoney = $orderValueClass->isEnoughMoney($client, $orderValue);
                break;
            case 'card_tips':

                $paymentStrategy = new TipsCashPayment();
                $isEnoughMoney = $orderValueClass->isEnoughMoney($client, $orderValue);
                break;
            default:
                throw new Exception('wrong payment strategy');
        }

        try {
            if ($isEnoughMoney) {
                $paymentStrategy->pay($client, $client->getConnectedOrder());
                $client->setStatus(Client::ORDER_PAYED);
                $this->em->flush();
            } else {
                throw new Exception('Customer dont have enough money!');
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