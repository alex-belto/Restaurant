<?php

namespace App\Services\Waiter;

use App\Entity\Client;
use App\Entity\Order;
use App\Interfaces\OrderManagerInterface;
use App\Interfaces\StaffInterface;
use App\Repository\MenuItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderManager implements OrderManagerInterface
{
    public function processingOrder(Client $client, ?StaffInterface $staff = null): void
    {
        /** @var MenuItemRepository $menuItemRepository */
        $menuItemRepository = MenuItemRepository::class;
        /** @var EntityManagerInterface $em */
        $em = EntityManagerInterface::class;

        $order = new Order();
        $order->setClient($client);
        $order->setWaiter($staff);
        $order->setStatus(Order::READY_TO_KITCHEN);

        for ($i = 0; $i <= 5; $i++) {
            $itemId = rand(1, 19);
            $menuItem = $menuItemRepository->find(['id' => $itemId]);
            $order->addMenuItem($menuItem);
            $em->persist($order);
            $em->flush();
        }
    }

}