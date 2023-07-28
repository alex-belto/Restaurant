<?php

namespace App\Tests\Unit\Services\Payment;

use App\Entity\Client;
use App\Services\Payment\CashPaymentProcessor;
use App\Services\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CashPaymentProcessorTest extends TestCase
{
    public function testCashPaymentProcess(): void
    {
        $client = $this->createMock(Client::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $processingPayment = $this->createMock(Payment::class);

        $client->method('isEnoughMoney')->willReturn(true);

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

        $cashPaymentProcessor = new CashPaymentProcessor($processingPayment, $em);
        $cashPaymentProcessor->pay($client);
    }

}