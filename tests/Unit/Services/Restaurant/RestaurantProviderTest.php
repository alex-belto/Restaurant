<?php

namespace App\Tests\Unit\Services\Restaurant;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use App\Services\Restaurant\RestaurantBuilder;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class RestaurantProviderTest extends TestCase
{
    private RestaurantBuilder $restaurantBuilder;
    private EntityManagerInterface $em;
    private RestaurantProvider $restaurantProvider;
    private string $filePath;
    private RestaurantRepository $restaurantRepository;
    private Restaurant $restaurant;

    public function setUp(): void
    {
        $this->restaurantBuilder = $this->createMock(RestaurantBuilder::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->restaurantProvider = new RestaurantProvider($this->restaurantBuilder, $this->em);
        $this->filePath = realpath(__DIR__ . '/../../../..') . $_ENV['FILE_PATH'];
        $this->restaurantRepository = $this->createMock(RestaurantRepository::class);
        $this->restaurant = $this->createMock(Restaurant::class);
    }

    public function tearDown(): void
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    public function testNotExistRestaurant(): void
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }

        $this->em->method('getRepository')->willReturn($this->restaurantRepository);

        $this->restaurantBuilder
            ->expects($this->once())
            ->method('buildRestaurant')
            ->willReturn($this->restaurant);

        $this->em
            ->expects($this->never())
            ->method('find');

        $restaurant = $this->restaurantProvider->getRestaurant(1);
        $this->assertInstanceOf(Restaurant::class, $restaurant);
    }

    public function testRestaurantFileExist(): void
    {
        $restaurantId = 111;
        file_put_contents($this->filePath, $restaurantId);

        $this->restaurantBuilder
            ->expects($this->never())
            ->method('buildRestaurant');

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Restaurant::class))
            ->willReturn($this->restaurantRepository);

        $this->restaurantRepository
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo($restaurantId))
            ->willReturn($this->restaurant);

        $restaurant = $this->restaurantProvider->getRestaurant();
        $this->assertInstanceOf(Restaurant::class, $restaurant);
    }

    public function testRestaurantFileExistButRestaurantNotFound(): void
    {
        $restaurantId = 111;
        file_put_contents($this->filePath, $restaurantId);

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Restaurant::class))
            ->willReturn($this->restaurantRepository);

       $this->restaurantRepository
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo($restaurantId))
            ->willReturn(null);

        $this->restaurantBuilder
            ->expects($this->once())
            ->method('buildRestaurant')
            ->willReturn($this->restaurant);

        $restaurant = $this->restaurantProvider->getRestaurant(1);
        $this->assertInstanceOf(Restaurant::class, $restaurant);
    }

}