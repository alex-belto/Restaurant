<?php

namespace App\Tests\Unit\Services\Payment;

use App\Entity\Client;
use App\Enum\ClientStatus;
use App\Exception\CardValidationException;
use App\Services\Payment\CardPaymentProcessor;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CardPaymentProcessorTest extends TestCase
{
    private EntityManagerInterface $em;
    private Client $client;
    private CardPaymentProcessor $cardPaymentProcessor;
    private Connection $connection;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->client = $this->createMock(Client::class);
        $this->cardPaymentProcessor = new CardPaymentProcessor($this->em);
        $this->connection = $this->createMock(Connection::class);
    }
    public function testCardPaymentProcess(): void
    {
        $this->client
            ->expects($this->once())
            ->method('isCardValid')
            ->willReturn(true);

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

        $this->cardPaymentProcessor->pay($this->client);
    }

    public function testCardValidationException(): void
    {
        $this->client
            ->expects($this->once())
            ->method('isCardValid')
            ->willReturn(false);

        $this->expectException(CardValidationException::class);
        $this->cardPaymentProcessor->pay($this->client);
    }

}