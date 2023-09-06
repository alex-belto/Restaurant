<?php

namespace App\Tests\Functional\Services\Cleaner;

use App\Entity\Order;
use App\Services\Cleaner\DataCleaner;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class DataCleanerTest extends TestCase
{
    private EntityManagerInterface $em;
    private DataCleaner $dataCleaner;
    private RestaurantProvider $restaurantProvider;
    private EntityRepository $entityRepository;
    private Order $order;
    private string $filePath;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->restaurantProvider = $this->createMock(RestaurantProvider::class);
        $this->entityRepository = $this->createMock(EntityRepository::class);
        $this->order = $this->createMock(Order::class);
        $this->filePath =  __DIR__ . $_ENV['FILE_PATH'];
        $this->dataCleaner = new DataCleaner($this->em, $this->restaurantProvider);
    }

    public function testRestaurantClosed(): void
    {
        file_put_contents($this->filePath, 111);

        $this->restaurantProvider
            ->expects($this->once())
            ->method('getFilePath')
            ->willReturn($this->filePath);

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Order::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($this->order);

        $message = $this->dataCleaner->removeRestaurantData();
        $this->assertEquals('Restaurant closed!', $message);
    }

    public function testRestaurantNotFound(): void
    {
        $this->restaurantProvider
            ->expects($this->once())
            ->method('getFilePath')
            ->willReturn($this->filePath);

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Order::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($this->order);

        $message = $this->dataCleaner->removeRestaurantData();
        $this->assertEquals('Restaurant not found!', $message);
    }
}