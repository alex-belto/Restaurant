<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\MenuItem;

/**
 * Calculates the total cost of an order,
 * including tips, and checks if the client has sufficient funds.
 */
class OrderValue
{
    /**
     * @param Client $client
     * @param int|null $tips
     * @return float
     */
    public function getOrderValue(Client $client, int $tips = null): float
    {
        $orderItems = $client->getConnectedOrder();
        $orderPrice = 0;

        /** @var MenuItem $orderItem */
        foreach ($orderItems->getMenuItems() as $orderItem)
        {
            $price = $orderItem->getPrice();
            $orderPrice += $price;
        }

        if ($tips) {
            $orderPrice += $orderPrice/100 * $tips;
        }

        return $orderPrice;
    }

    public function isEnoughMoney(Client $client, float $orderValue = null): bool
    {
        if ($orderValue === null) {
            $orderValue = $this->getOrderValue($client);
        }

        $customersMoney = $client->getMoney();

        return $customersMoney >= $orderValue;
    }

}