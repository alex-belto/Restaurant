<?php

namespace App\Services\OrderItem;

use App\Entity\MenuItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\MenuItemType;

/**
 * Class that creates OrderItem instances for the restaurant system.
 */
class OrderItemFactory
{
    public function createOrderItem(MenuItem $menuItem, Order $order): OrderItem
    {
        $type = match ($menuItem->getType()) {
            MenuItemType::DISH => 'dish',
            MenuItemType::DRINK => 'drink'
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