<?php

namespace App\Tests\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\Payment\CashPaymentProcessor;
use App\Services\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CashPaymentProcessorTest extends TestCase
{
    public function testCashPaymentProcess(): void
    {
        $processingPayment = $this->getMockBuilder(Payment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $em = $this->createMock(EntityManagerInterface::class);

        $cashPaymentProcessor = new CashPaymentProcessor($processingPayment, $em);

        $client = $this->createMock(Client::class);
        $order = $this->createMock(Order::class);

        $client->method('isEnoughMoney')->willReturn(true);

        $processingPayment->expects($this->once())
            ->method('payOrder')
            ->with($this->equalTo($client), $this->equalTo($order));

        $em->expects($this->atLeastOnce())
            ->method('getConnection')
            ->willReturnSelf();

        $em->expects($this->once())
            ->method('beginTransaction');

        $client->expects($this->once())
            ->method('setStatus')
            ->with(Client::ORDER_PAYED);

        $em->expects($this->once())
            ->method('flush');

        $em->expects($this->once())
            ->method('commit');

        $cashPaymentProcessor->pay($client, $order);
    }

}