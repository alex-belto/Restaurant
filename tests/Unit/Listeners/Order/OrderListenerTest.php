<?php

namespace App\Tests\Unit\Listeners\Order;

use App\Entity\Client;
use App\Entity\Order;
use App\Enum\OrderStatus;
use App\EventListener\Order\OrderListener;
use App\Services\Payment\PaymentHandler;
use PHPUnit\Framework\TestCase;

class OrderListenerTest extends TestCase
{
    private PaymentHandler $paymentHandler;
    private Order $order;
    private OrderListener $orderListener;
    private Client $client;

    public function setUp(): void
    {
        $this->paymentHandler = $this->createMock(PaymentHandler::class);
        $this->order = $this->createMock(Order::class);
        $this->orderListener = new OrderListener($this->paymentHandler);
        $this->client = $this->createMock(Client::class);
    }

    public function testOrderNotDone(): void
    {
        $this->order
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn(OrderStatus::READY_TO_WAITER);

        $this->order
            ->expects($this->never())
            ->method('getClient')
            ->willReturn($this->client);

        $this->paymentHandler
            ->expects($this->never())
            ->method('payOrder');

        $this->orderListener->payOrder($this->order);
    }

    public function testOrderDone(): void
    {
        $this->order
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn(OrderStatus::DELIVERED);

        $this->order
            ->expects($this->once())
            ->method('getClient')
            ->willReturn($this->client);

        $this->paymentHandler
            ->expects($this->once())
            ->method('payOrder');

        $this->orderListener->payOrder($this->order);
    }
}