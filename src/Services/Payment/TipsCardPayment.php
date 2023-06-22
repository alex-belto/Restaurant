<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\PaymentInterface;
use App\Services\Tips\ProcessingTips;
use App\Services\Tips\TipsStandardStrategy;
use App\Services\Tips\TipsWaiterStrategy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TipsCardPayment implements PaymentInterface
{
    /**
     * @throws \Exception
     */
    public function pay(Client $client, Order $order): void
    {
        $cardPayment = new CardPayment();
        $cardPayment->pay($client, $order);
        $processingTips = new ProcessingTips();
        $processingTips($order);
    }
}