<?php

namespace App\Tests\Unit\Services\Payment;

use App\Entity\Client;
use App\Services\Payment\CashPaymentProcessor;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CashPaymentProcessorTest extends TestCase
{
    public function testCashPaymentProcess(): void
    {
        $client = $this->createMock(Client::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $connection = $this->createMock(Connection::class);

        $client
            ->expects($this->once())
            ->method('payOrder');

        $em
            ->expects($this->atLeast(2))
            ->method('getConnection')
            ->willReturn($connection);

        $connection
            ->expects($this->once())
            ->method('beginTransaction');

        $client
            ->expects($this->once())
            ->method('setStatus')
            ->with(Client::ORDER_PAYED);

        $em
            ->expects($this->once())
            ->method('flush');

        $connection
            ->expects($this->once())
            ->method('commit');

        $cashPaymentProcessor = new CashPaymentProcessor($em);
        $cashPaymentProcessor->pay($client);
    }

}