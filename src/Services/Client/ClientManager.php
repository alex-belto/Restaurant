<?php

namespace App\Services\Client;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Responsible for managing clients orders.
 */
class ClientManager
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
    ) {
        $this->em = $em;
    }

    public function makeOrder(Client $client, Restaurant $restaurant): Order
    {
        $order = new Order();
        $order->setClient($client);
        $order->setStatus(Order::READY_TO_KITCHEN);
        $menu = $restaurant->getMenuItems()->toArray();

        for ($i = 0; $i < 5; $i++) {
            $item = rand(0, 18);
            $menuItem = $menu[$item];
            $order->addMenuItem($menuItem);
            $this->em->persist($order);
        }

        $client->setStatus(Client::ORDER_PLACED);
        $client->setConnectedOrder($order);
        $this->em->flush();
        return $order;
    }

}