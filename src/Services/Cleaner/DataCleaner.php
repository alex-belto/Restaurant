<?php

namespace App\Services\Cleaner;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * A utility class responsible for cleansing data for restaurant operations.
 */
class DataCleaner
{
    private EntityManagerInterface $em;
    private RestaurantProvider $restaurantProvider;

    public function __construct(
        EntityManagerInterface $em,
        RestaurantProvider $restaurantProvider
    ) {
        $this->em = $em;
        $this->restaurantProvider = $restaurantProvider;
    }

    public function removeRestaurantFile(): string
    {
        $filePath = $this->restaurantProvider->getFilePath();
        if (!file_exists($filePath)) {
            return 'Restaurant not found!';
        }

        unlink($filePath);
        return 'Restaurant closed!';
    }

    public function removeAllClients(): void
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->delete(Client::class)
            ->getQuery()
            ->execute();
    }

    public function removeAllOrders(): void
    {
        $qb = $this->em->createQueryBuilder();
        $qbOrderItem = $this->em->createQueryBuilder();

        $qbOrderItem
            ->update(OrderItem::class, 'oi')
            ->set('oi.connectedOrder', 'NULL')
            ->getQuery()
            ->execute();

        $qb
            ->update(Order::class, 'o')
            ->set('o.client', 'NULL')
            ->getQuery()
            ->execute();

        $qb
            ->delete(Order::class)
            ->getQuery()
            ->execute();
    }
}