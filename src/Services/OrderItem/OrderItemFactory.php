<?php

namespace App\Services\OrderItem;

use App\Entity\MenuItem;
use App\Entity\Order;
use App\Entity\OrderItem;

class OrderItemFactory
{
    public function createOrderItem(MenuItem $menuItem, Order $order): OrderItem
    {
        $type = match ($menuItem->getType()) {
            MenuItem::DISH => 'dish',
            MenuItem::DRINK => 'drink'
        };
        $orderItem = new OrderItem();
        $orderItem->setConnectedOrder($order);
        $orderItem->addMenuItem($menuItem);
        $orderItem->setName($menuItem->getName());
        $orderItem->setPrice($menuItem->getPrice());
        $orderItem->setType($type);

        return $orderItem;
    }

}