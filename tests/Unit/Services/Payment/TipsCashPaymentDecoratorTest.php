<?php

namespace App\Tests\Unit\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\Payment\CashPaymentProcessor;
use App\Services\Payment\TipsCashPaymentDecorator;
use App\Services\Tips\TipsDistributor;
use PHPUnit\Framework\TestCase;

class TipsCashPaymentDecoratorTest extends TestCase
{
    public function testTipsCashPayment(): void
    {
        $client = $this->createMock(Client::class);
        $order = $this->createMock(Order::class);
        $cashPaymentProcessor = $this->getMockBuilder(CashPaymentProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tipsDistributor = $this->getMockBuilder(TipsDistributor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->method('getConnectedOrder')->willReturn($order);

        $cashPaymentProcessor->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($client));

        $tipsDistributor->expects($this->once())
            ->method('splitTips')
            ->with($this->equalTo($order));

        $tipsCashPaymentDecorator = new TipsCashPaymentDecorator($tipsDistributor, $cashPaymentProcessor);
        $tipsCashPaymentDecorator->pay($client);
    }

}