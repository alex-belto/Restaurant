<?php

namespace App\Services\Waiter;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\OrderManagerInterface;
use App\Interfaces\StaffInterface;
use App\Repository\MenuItemRepository;
use App\Repository\WaiterRepository;
use Doctrine\ORM\EntityManagerInterface;

class WaiterOrderManager implements OrderManagerInterface
{

    /**
     * @var WaiterRepository
     */
    private $waiterRepository;

    public function __construct(WaiterRepository $waiterRepository) {
        $this->waiterRepository = $waiterRepository;
    }

    public function processingOrder(Client $client, ?StaffInterface $staff = null): void
    {
        /** @var MenuItemRepository $menuItemRepository */
        $menuItemRepository = MenuItemRepository::class;
        /** @var EntityManagerInterface $em */
        $em = EntityManagerInterface::class;
        $waiter = $this->chooseWaiter();

        $order = new Order();
        $order->setClient($client);
        $order->setWaiter($waiter);
        $order->setStatus(Order::READY_TO_KITCHEN);

        for ($i = 0; $i <= 5; $i++) {
            $itemId = rand(1, 19);
            $menuItem = $menuItemRepository->find(['id' => $itemId]);
            $order->addMenuItem($menuItem);
            $em->persist($order);
            $em->flush();
        }
    }

    public function chooseWaiter(): Waiter
    {
        $amountOfWaiter = count($this->waiterRepository->findAll());
        $randomWaiter = rand(1, $amountOfWaiter);
        return $this->waiterRepository->find(['id' => $randomWaiter]);
    }

}