<?php

namespace App\Tests\Unit\Services\Payment;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\Payment\CardPaymentProcessor;
use App\Services\Payment\CashPaymentProcessor;
use App\Services\Payment\PaymentHandler;
use App\Services\Payment\TipsCardPaymentDecorator;
use App\Services\Payment\TipsCashPaymentDecorator;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PaymentHandlerTest extends TestCase
{
    private ContainerInterface $container;
    private Client $client;
    private Order $order;
    private PaymentHandler $paymentHandler;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->client = $this->createMock(Client::class);
        $this->order = $this->createMock(Order::class);
        $this->paymentHandler = new PaymentHandler($this->container);
    }

    public function testPayOrderByCash(): void
    {
        $cashPaymentProcessor = $this->createMock(CashPaymentProcessor::class);

        $this->client->method('isEnoughMoney')->willReturn(true);
        $this->client->method('getConnectedOrder')->willReturn($this->order);
        $this->client->method('getPaymentMethod')->willReturn('cashPayment');

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->client->getPaymentMethod()))
            ->willReturn($cashPaymentProcessor);

        $cashPaymentProcessor
            ->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($this->client));

        $this->paymentHandler->payOrder($this->client);
    }

    public function testPayOrderByCard(): void
    {
        $cardPaymentProcessor = $this->createMock(CardPaymentProcessor::class);

        $this->client->method('isEnoughMoney')->willReturn(true);
        $this->client->method('getConnectedOrder')->willReturn($this->order);
        $this->client->method('getPaymentMethod')->willReturn('cardPayment');

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->client->getPaymentMethod()))
            ->willReturn($cardPaymentProcessor);

        $cardPaymentProcessor
            ->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($this->client));

        $this->paymentHandler->payOrder($this->client);
    }

    public function testPayOrderByCashWithTips(): void
    {
        $tipsCashPaymentDecorator = $this->createMock(TipsCashPaymentDecorator::class);

        $this->client->method('isEnoughMoney')->willReturn(true);
        $this->client->method('getConnectedOrder')->willReturn($this->order);
        $this->client->method('getPaymentMethod')->willReturn('tipsCashPayment');

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->client->getPaymentMethod()))
            ->willReturn($tipsCashPaymentDecorator);

        $tipsCashPaymentDecorator
            ->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($this->client));

        $this->paymentHandler->payOrder($this->client);
    }

    public function testPayOrderByCardWithTips(): void
    {
        $tipsCardPaymentDecorator = $this->createMock(TipsCardPaymentDecorator::class);

        $this->client->method('isEnoughMoney')->willReturn(true);
        $this->client->method('getConnectedOrder')->willReturn($this->order);
        $this->client->method('getPaymentMethod')->willReturn('tipsCardPayment');

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->client->getPaymentMethod()))
            ->willReturn($tipsCardPaymentDecorator);

        $tipsCardPaymentDecorator
            ->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($this->client));

        $this->paymentHandler->payOrder($this->client);
    }

    public function testClientDontHaveEnoughMoney(): void
    {
        $this->client->method('getPaymentMethod')->willReturn('cardPayment');
        $this->client->method('isEnoughMoney')->willReturn(false);
        $this->expectException(Exception::class);

        $paymentHandler = new PaymentHandler($this->container);
        $paymentHandler->payOrder($this->client);
    }
}