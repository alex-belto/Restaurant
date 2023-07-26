<?php

namespace App\Tests\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\Payment\CardPaymentProcessor;
use App\Services\Payment\TipsCardPaymentDecorator;
use App\Services\Tips\TipsDistributor;
use PHPUnit\Framework\TestCase;

class TipsCardPaymentDecoratorTest extends TestCase
{
    public function testTipsCardPayment(): void
    {
        $client = $this->createMock(Client::class);
        $order = $this->createMock(Order::class);
        $cardPaymentProcessor = $this->getMockBuilder(CardPaymentProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tipsDistributor = $this->getMockBuilder(TipsDistributor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cardPaymentProcessor->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($client), $this->equalTo($order));

        $tipsDistributor->expects($this->once())
            ->method('splitTips')
            ->with($this->equalTo($order));

        $tipsCardPaymentDecorator = new TipsCardPaymentDecorator($tipsDistributor, $cardPaymentProcessor);
        $tipsCardPaymentDecorator->pay($client, $order);
    }

}