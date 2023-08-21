<?php

namespace App\Tests\Unit\Services\Cleaner;

use App\Services\Cleaner\DataCleaner;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class DataCleanerTest extends TestCase
{
    private EntityManagerInterface $em;
    private QueryBuilder $queryBuilder;
    private AbstractQuery $query;
    private DataCleaner $dataCleaner;
    private RestaurantProvider $restaurantProvider;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->query = $this->createMock(AbstractQuery::class);
        $this->restaurantProvider = $this->createMock(RestaurantProvider::class);
        $this->dataCleaner = new DataCleaner($this->em, $this->restaurantProvider);
    }

    public function testRemoveAllOrders(): void
    {

        $this->em
            ->expects($this->exactly(2))
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->expects($this->exactly(2))
            ->method('update')
            ->willReturnSelf();

        $this->queryBuilder
            ->expects($this->exactly(2))
            ->method('set')
            ->willReturnSelf();

        $this->queryBuilder
            ->expects($this->once())
            ->method('delete')
            ->willReturnSelf();

        $this->queryBuilder
            ->expects($this->exactly(3))
            ->method('getQuery')
            ->willReturn($this->query);

        $this->query
            ->expects($this->exactly(3))
            ->method('execute');

        $this->dataCleaner->removeAllOrders();
    }

    public function removeAllClients(): void
    {
        $this->em
            ->expects($this->exactly(1))
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->expects($this->once())
            ->method('delete')
            ->willReturnSelf();

        $this->queryBuilder
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->query);

        $this->query
            ->expects($this->once())
            ->method('execute');

        $this->dataCleaner->removeAllOrders();
    }
}