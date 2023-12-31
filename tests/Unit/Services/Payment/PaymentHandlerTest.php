<?php

namespace App\Tests\Unit\Services\Payment;

use App\Entity\Client;
use App\Enum\ClientStatus;
use App\Exception\CardValidationException;
use App\Interfaces\PaymentInterface;
use App\Services\Payment\PaymentHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;

class PaymentHandlerTest extends TestCase
{
    private ServiceLocator $container;
    private Client $client;
    private PaymentHandler $paymentHandler;
    private PaymentInterface $paymentInterface;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ServiceLocator::class);
        $this->client = $this->createMock(Client::class);
        $this->paymentHandler = new PaymentHandler($this->container);
        $this->paymentInterface = $this->createMock(PaymentInterface::class);
    }

    public function testPayOrder(): void
    {
        $this->client
            ->expects($this->once())
            ->method('isEnoughMoneyForOrder')
            ->willReturn(true);

        $this->client
            ->expects($this->once())
            ->method('getPaymentMethod')
            ->willReturn('cashPayment');

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('cashPayment'))
            ->willReturn($this->paymentInterface);

        $this->paymentInterface
            ->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($this->client));

        $this->paymentHandler->payOrder($this->client);
    }

    public function testCardNotValidException(): void
    {
        $cardValidationException = $this->createMock(CardValidationException::class);

        $this->client
            ->expects($this->once())
            ->method('isEnoughMoneyForOrder')
            ->willReturn(true);

        $this->client
            ->expects($this->once())
            ->method('getPaymentMethod');

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturn($this->paymentInterface);

        $this->paymentInterface
            ->expects($this->exactly(2))
            ->method('pay')
            ->with($this->equalTo($this->client))
            ->willThrowException($cardValidationException);

        $this->expectException(CardValidationException::class);
        $this->paymentHandler->payOrder($this->client);
    }

    public function testClientDontHaveEnoughMoney(): void
    {
        $this->client
            ->expects($this->once())
            ->method('getPaymentMethod')
            ->willReturn('cardPayment');

        $this->client
            ->expects($this->once())
            ->method('isEnoughMoneyForOrder')
            ->willReturn(false);

        $this->expectExceptionMessage('Client dont have enough money!');

        $this->paymentHandler->payOrder($this->client);
    }
}