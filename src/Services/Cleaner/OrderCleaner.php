<?php

namespace App\Services\Cleaner;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;

class OrderCleaner
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
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