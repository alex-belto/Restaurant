<?php

namespace App\Tests\Unit\Listeners\Order;

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

    public function setUp(): void
    {
        $this->paymentHandler = $this->createMock(PaymentHandler::class);
        $this->order = $this->createMock(Order::class);
        $this->orderListener = new OrderListener($this->paymentHandler);
    }

    public function testOrderNotDone(): void
    {
        $this->order
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn(OrderStatus::READY_TO_WAITER->getIndex());

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
            ->willReturn(OrderStatus::DONE->getIndex());

        $this->paymentHandler
            ->expects($this->once())
            ->method('payOrder');

        $this->orderListener->payOrder($this->order);
    }
}