<?php

namespace App\Tests\Services;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\Payment\CardPaymentProcessor;
use App\Services\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CardPaymentProcessorTest extends TestCase
{
    public function testCardPaymentProcess(): void
    {
        $processingPayment = $this->getMockBuilder(Payment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $em = $this->createMock(EntityManagerInterface::class);

        $cardPaymentProcessor = new CardPaymentProcessor($processingPayment, $em);

        $client = $this->createMock(Client::class);
        $order = $this->createMock(Order::class);

        $client->method('isEnoughMoney')->willReturn(true);
        $client->method('isCardValid')->willReturn(true);

        $processingPayment->expects($this->once())
            ->method('payOrder')
            ->with($this->equalTo($client), $this->equalTo($order));

        $em->expects($this->atLeastOnce())
            ->method('getConnection')
            ->willReturnSelf();

        $em->expects($this->once())
            ->method('beginTransaction');

        $em->expects($this->once())
            ->method('flush');

        $em->expects($this->once())
            ->method('commit');

        $cardPaymentProcessor->pay($client, $order);
    }

}