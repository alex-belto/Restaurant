<?php

namespace App\Services\Client;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Services\OrderItem\OrderItemFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Responsible for managing clients orders.
 */
class ClientManager
{
    private EntityManagerInterface $em;
    private OrderItemFactory $orderItemFactory;

    public function __construct(
        EntityManagerInterface $em,
        OrderItemFactory $orderItemFactory
    ) {
        $this->em = $em;
        $this->orderItemFactory = $orderItemFactory;
    }

    public function makeOrder(Client $client, Restaurant $restaurant): Order
    {
        $order = new Order();
        $order->setClient($client);
        $menu = $restaurant->getMenuItems()->toArray();
        $amountOfMenuItems = count($menu) - 1;

        for ($i = 0; $i < 3; $i++) {
            $item = rand(0, $amountOfMenuItems);
            $menuItem = $menu[$item];
            $orderItem = $this->orderItemFactory->createOrderItem($menuItem, $order);
            $this->em->persist($orderItem);
            $order->addOrderItem($orderItem);
        }

        $client->setStatus(Client::ORDER_PLACED);
        $client->setConnectedOrder($order);
        $this->em->persist($order);
        $this->em->flush();
        return $order;
    }

}