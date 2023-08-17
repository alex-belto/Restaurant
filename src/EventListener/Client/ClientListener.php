<?php

namespace App\EventListener\Client;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\OrderItem\OrderItemFactory;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * After the client is persisted in the system, we proceed to create an order for them.
 */
class ClientListener
{
    private RestaurantProvider $restaurantProvider;
    private OrderItemFactory $orderItemFactory;
    private EntityManagerInterface $em;

    public function __construct(
        RestaurantProvider $restaurantProvider,
        OrderItemFactory $orderItemFactory,
        EntityManagerInterface $em
    ) {
        $this->restaurantProvider = $restaurantProvider;
        $this->orderItemFactory = $orderItemFactory;
        $this->em = $em;
    }

    public function makeOrder(Client $client): Order
    {
        $restaurant = $this->restaurantProvider->getRestaurant();
        $order = new Order();
        $order->setClient($client);
        $menu = $restaurant->getMenuItems();
        $amountOfMenuItems = $menu->count() - 1;

        for ($i = 0; $i < 3; $i++) {
            $item = rand(0, $amountOfMenuItems);
            $menuItem = $menu->get($item);
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