<?php

namespace App\Tests\Unit\Services\Payment;

use App\Entity\Client;
use App\Enum\ClientStatus;
use App\Services\Payment\CashPaymentProcessor;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Exception;

class CashPaymentProcessorTest extends TestCase
{
    private Client $client;
    private EntityManagerInterface $em;
    private Connection $connection;
    private CashPaymentProcessor $cashPaymentProcessor;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->connection = $this->createMock(Connection::class);
        $this->cashPaymentProcessor = new CashPaymentProcessor($this->em);
    }

    public function testCashPaymentProcess(): void
    {
        $this->client
            ->expects($this->once())
            ->method('payOrder');

        $this->em
            ->expects($this->atLeast(2))
            ->method('getConnection')
            ->willReturn($this->connection);

        $this->connection
            ->expects($this->once())
            ->method('beginTransaction');

        $this->client
            ->expects($this->once())
            ->method('setStatus')
            ->with(ClientStatus::ORDER_PAYED->getIndex());

        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->connection
            ->expects($this->once())
            ->method('commit');

        $this->cashPaymentProcessor->pay($this->client);
    }

    public function testCashPaymentWithException(): void
    {
        $this->em
            ->expects($this->atLeast(2))
            ->method('getConnection')
            ->willReturn($this->connection);

        $this->connection
            ->expects($this->once())
            ->method('beginTransaction');

        $this->client
            ->expects($this->once())
            ->method('payOrder');

        $this->client
            ->expects($this->once())
            ->method('setStatus')
            ->with(ClientStatus::ORDER_PAYED->getIndex());

        $this->em
            ->expects($this->once())
            ->method('flush')
            ->willThrowException(new Exception());

        $this->expectException(Exception::class);

        $this->connection
            ->expects($this->once())
            ->method('rollBack');

        $this->cashPaymentProcessor->pay($this->client);
    }

}