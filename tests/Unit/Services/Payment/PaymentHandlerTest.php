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
    public function testPayOrderByCash(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $client = $this->createMock(Client::class);
        $order = $this->createMock(Order::class);

        $cashPaymentProcessor = $this->getMockBuilder(CashPaymentProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->method('isEnoughMoney')->willReturn(true);
        $client->method('getConnectedOrder')->willReturn($order);
        $client->method('getPaymentMethod')->willReturn('cashPayment');

        $container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($client->getPaymentMethod()))
            ->willReturn($cashPaymentProcessor);

        $cashPaymentProcessor->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($client), $this->equalTo($order));

        $paymentHandler = new PaymentHandler($container);
        $paymentHandler->payOrder($client);
    }

    public function testPayOrderByCard(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $client = $this->createMock(Client::class);
        $order = $this->createMock(Order::class);

        $cardPaymentProcessor = $this->getMockBuilder(CardPaymentProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->method('isEnoughMoney')->willReturn(true);
        $client->method('getConnectedOrder')->willReturn($order);
        $client->method('getPaymentMethod')->willReturn('cardPayment');

        $container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($client->getPaymentMethod()))
            ->willReturn($cardPaymentProcessor);

        $cardPaymentProcessor->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($client), $this->equalTo($order));

        $paymentHandler = new PaymentHandler($container);
        $paymentHandler->payOrder($client);
    }

    public function testPayOrderByCashWithTips(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $client = $this->createMock(Client::class);
        $order = $this->createMock(Order::class);
        $tipsCashPaymentDecorator = $this->getMockBuilder(TipsCashPaymentDecorator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->method('isEnoughMoney')->willReturn(true);
        $client->method('getConnectedOrder')->willReturn($order);
        $client->method('getPaymentMethod')->willReturn('tipsCashPayment');

        $container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($client->getPaymentMethod()))
            ->willReturn($tipsCashPaymentDecorator);

        $tipsCashPaymentDecorator->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($client), $this->equalTo($order));

        $paymentHandler = new PaymentHandler($container);
        $paymentHandler->payOrder($client);
    }

    public function testPayOrderByCardWithTips(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $client = $this->createMock(Client::class);
        $order = $this->createMock(Order::class);
        $tipsCardPaymentDecorator = $this->getMockBuilder(TipsCardPaymentDecorator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->method('isEnoughMoney')->willReturn(true);
        $client->method('getConnectedOrder')->willReturn($order);
        $client->method('getPaymentMethod')->willReturn('tipsCardPayment');

        $container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($client->getPaymentMethod()))
            ->willReturn($tipsCardPaymentDecorator);

        $tipsCardPaymentDecorator->expects($this->once())
            ->method('pay')
            ->with($this->equalTo($client), $this->equalTo($order));

        $paymentHandler = new PaymentHandler($container);
        $paymentHandler->payOrder($client);
    }

    public function testIsEnoughMoney(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $client = $this->createMock(Client::class);

        $client->method('getPaymentMethod')->willReturn('cardPayment');
        $client->method('isEnoughMoney')->willReturn(false);
        $this->expectException(Exception::class);

        $paymentHandler = new PaymentHandler($container);
        $paymentHandler->payOrder($client);
    }
}