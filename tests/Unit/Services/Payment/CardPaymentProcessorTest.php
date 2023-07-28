<?php

namespace App\Tests\Unit\Services\Payment;

use App\Entity\Client;
use App\Exception\CardValidationException;
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

        $client->method('isEnoughMoney')->willReturn(true);
        $client->method('isCardValid')->willReturn(true);

        $processingPayment
            ->expects($this->once())
            ->method('payOrder')
            ->with($this->equalTo($client));

        $em
            ->expects($this->atLeastOnce())
            ->method('getConnection')
            ->willReturnSelf();

        $em
            ->expects($this->once())
            ->method('beginTransaction');

        $client
            ->expects($this->once())
            ->method('setStatus')
            ->with(Client::ORDER_PAYED);

        $em
            ->expects($this->once())
            ->method('flush');

        $em
            ->expects($this->once())
            ->method('commit');

        $cardPaymentProcessor->pay($client);
    }

    public function testCardValidationException(): void
    {
        $client = $this->createMock(Client::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $processingPayment = $this->getMockBuilder(Payment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->method('isCardValid')->willReturn(false);
        $this->expectException(CardValidationException::class);

        $cardPaymentProcessor = new CardPaymentProcessor($processingPayment, $em);
        $cardPaymentProcessor->pay($client);
    }

}