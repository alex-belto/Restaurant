<?php

namespace App\Tests\Unit\Listeners\Client;

use App\Entity\Client;
use App\Entity\MenuItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Restaurant;
use App\Enum\ClientStatus;
use App\EventListener\Client\ClientListener;
use App\Services\OrderItem\OrderItemFactory;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ClientListenerTest extends TestCase
{
    public function testMakeOrder(): void
    {
        $restaurantProvider = $this->createMock(RestaurantProvider::class);
        $orderItemFactory = $this->createMock(OrderItemFactory::class);
        $orderItemOne = $this->createMock(OrderItem::class);
        $orderItemTwo = $this->createMock(OrderItem::class);
        $orderItemThree = $this->createMock(OrderItem::class);
        $mockOrder = $this->createMock(Order::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $clientListener = new ClientListener($restaurantProvider, $orderItemFactory, $em);
        $client = $this->createMock(Client::class);
        $restaurant = $this->createMock(Restaurant::class);
        $menuItem = $this->createMock(MenuItem::class);
        $menuFill = array_fill(0, 4, $menuItem);
        $menu = new ArrayCollection($menuFill);

        $restaurantProvider
            ->expects($this->once())
            ->method('getRestaurant')
            ->willReturn($restaurant);

        $mockOrder->setClient($client);

        $restaurant
            ->expects($this->once())
            ->method('getMenuItems')
            ->willReturn($menu);

        $orderItemFactory
            ->expects($this->exactly(3))
            ->method('createOrderItem')
            ->with($this->equalTo($menuItem), $this->isInstanceOf(Order::class))
            ->willReturnOnConsecutiveCalls($orderItemOne, $orderItemTwo, $orderItemThree);

        $client
            ->expects($this->once())
            ->method('setStatus')
            ->with($this->equalTo(ClientStatus::ORDER_PLACED));

        $client
            ->expects($this->once())
            ->method('setConnectedOrder')
            ->with($this->isInstanceOf(Order::class));

        $em
            ->expects($this->exactly(4))
            ->method('persist');

        $em
            ->expects($this->once())
            ->method('flush');

        $clientListener->makeOrder($client);
    }

}