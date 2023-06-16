<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\MenuItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderValue extends AbstractController
{
    /**
     * @param Client $client
     * @return float
     */
    public function getOrderValue(Client $client): float
    {
        $orderItems = $client->getMenuItems();
        $orderPrice = 0;

        /** @var MenuItem $orderItem */
        foreach ($orderItems as $orderItem)
        {
            $price = $orderItem->getPrice();
            $orderPrice += $price;
        }

        return $orderPrice;
    }

    public function isEnoughMoney(Client $client, float $orderValue = null): bool
    {
        if ($orderValue === null) {
            $orderValue = $this->getOrderValue($client);
        }

        $customersMoney = $client->getMoney();

        if ($customersMoney >= $orderValue) {
            return true;
        }

        return false;
    }

}