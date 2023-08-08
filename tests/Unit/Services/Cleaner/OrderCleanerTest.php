<?php

namespace App\Tests\Unit\Services\Cleaner;

use App\Services\Cleaner\OrderCleaner;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class OrderCleanerTest extends TestCase
{
    public function testRemoveAllOrders(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(AbstractQuery::class);
        $orderCleaner = new OrderCleaner($em);

        $em
            ->expects($this->exactly(2))
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->exactly(2))
            ->method('update')
            ->willReturnSelf();

        $queryBuilder
            ->expects($this->exactly(2))
            ->method('set')
            ->willReturnSelf();

        $queryBuilder
            ->expects($this->once())
            ->method('delete')
            ->willReturnSelf();

        $queryBuilder
            ->expects($this->exactly(3))
            ->method('getQuery')
            ->willReturn($query);

        $query
            ->expects($this->exactly(3))
            ->method('execute');

        $orderCleaner->removeAllOrders();
    }

}