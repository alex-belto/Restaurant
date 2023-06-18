<?php

namespace App\Services\Waiter;

use App\Entity\Client;
use App\Repository\MenuItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderManager
{
    public function makeOrder(Client $client): void
    {
        /** @var MenuItemRepository $menuItemRepository */
        $menuItemRepository = MenuItemRepository::class;
        /** @var EntityManagerInterface $em */
        $em = EntityManagerInterface::class;

        for ($i = 0; $i <= 5; $i++) {
            $itemId = rand(1, 19);
            $menuItem = $menuItemRepository->find(['id' => $itemId]);
            $client->addMenuItem($menuItem);
            $em->flush();
        }
    }

}