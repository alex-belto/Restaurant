<?php

namespace App\Services\Waiter;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Waiter;
use App\Interfaces\OrderManagerInterface;
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

    public function processingOrder(Order $order): void
    {
        /** @var MenuItemRepository $menuItemRepository */
        $menuItemRepository = MenuItemRepository::class;
        /** @var EntityManagerInterface $em */
        $em = EntityManagerInterface::class;
        $waiter = $this->chooseWaiter();
        $order->setWaiter($waiter);
    }

    public function chooseWaiter(): Waiter
    {
        $amountOfWaiter = count($this->waiterRepository->findAll());
        $randomWaiter = rand(1, $amountOfWaiter);
        return $this->waiterRepository->find(['id' => $randomWaiter]);
    }

}