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
    private EntityManagerInterface $em;
    private Client $client;
    private Payment $processingPayment;
    private CardPaymentProcessor $cardPaymentProcessor;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->client = $this->createMock(Client::class);
        $this->processingPayment = $this->createMock(Payment::class);
        $this->cardPaymentProcessor = new CardPaymentProcessor($this->processingPayment, $this->em);
    }
    public function testCardPaymentProcess(): void
    {
        $this->client->method('isEnoughMoney')->willReturn(true);
        $this->client->method('isCardValid')->willReturn(true);

        $this->processingPayment
            ->expects($this->once())
            ->method('payOrder')
            ->with($this->equalTo($this->client));

        $this->em
            ->expects($this->atLeastOnce())
            ->method('getConnection')
            ->willReturnSelf();

        $this->em
            ->expects($this->once())
            ->method('beginTransaction');

        $this->client
            ->expects($this->once())
            ->method('setStatus')
            ->with(Client::ORDER_PAYED);

        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->em
            ->expects($this->once())
            ->method('commit');

        $this->cardPaymentProcessor->pay($this->client);
    }

    public function testCardValidationException(): void
    {
        $this->client->method('isCardValid')->willReturn(false);
        $this->expectException(CardValidationException::class);

        $this->cardPaymentProcessor->pay($this->client);
    }

}