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
        $menu = $restaurant->getMenuItems()->toArray();
        $amountOfMenuItems = count($menu) - 1;

        for ($i = 0; $i < 3; $i++) {
            $item = rand(0, $amountOfMenuItems);
            $menuItem = $menu[$item];
            $order->addMenuItem($menuItem);
        }

        $client->setStatus(Client::ORDER_PLACED);
        $client->setConnectedOrder($order);
        $this->em->persist($order);
        $this->em->flush();
        return $order;
    }

}