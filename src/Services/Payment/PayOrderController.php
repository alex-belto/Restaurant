<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;
use Doctrine\ORM\EntityManagerInterface;

class PayOrderController
{
    /**
     * @throws \Exception
     */
    public function payOrder(Client $client, PaymentInterface $paymentStrategy, int $tips = null): void
    {
        /** @var EntityManagerInterface $em */
        $em = EntityManagerInterface::class;
        $orderValueClass = new OrderValue();
        $orderValue = $orderValueClass->getOrderValue($client);
        if ($tips) {
            $orderValue = $orderValueClass->getOrderValue($client, $tips);
        }

        switch ($paymentStrategy) {
            case 'cash':
                $paymentStrategy = new CashPayment();
                $isEnoughMoney = $orderValueClass->isEnoughMoney($client);
                break;
            case 'card':
                $paymentStrategy = new CardPayment();
                $isEnoughMoney = $orderValueClass->isEnoughMoney($client);
                break;
            case 'cash_tips':
                $paymentStrategy = new TipsCardPayment(new CardPayment());
                $paymentStrategy->setTips($tips);
                $isEnoughMoney = $orderValueClass->isEnoughMoney($client, $orderValue);
                break;
            case 'card_tips':

                $paymentStrategy = new TipsCashPayment(new CashPayment());
                $paymentStrategy->setTips($tips);
                $isEnoughMoney = $orderValueClass->isEnoughMoney($client, $orderValue);
                break;
            default:
                throw new \Exception('wrong payment strategy');
        }

        try {
            if ($isEnoughMoney) {
                $paymentStrategy->pay($client, $orderValue);
                $em->flush();
            } else {
                throw new \Exception('Customer dont have enough money!');
            }
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}