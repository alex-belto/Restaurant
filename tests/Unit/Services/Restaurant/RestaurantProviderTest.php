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

    public function setUp(): void
    {
        $this->restaurantBuilder = $this->createMock(RestaurantBuilder::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->restaurantProvider = new RestaurantProvider($this->restaurantBuilder, $this->em);
        $this->filePath = realpath(__DIR__ . '/../../../..') . $_ENV['FILE_PATH'];
    }

//    public function testNotExistRestaurant(): void
//    {
//        if (file_exists($this->filePath)) {
//            unlink($this->filePath);
//        }
//
//        $restaurant = $this->createMock(Restaurant::class);
//        $restaurantRepository = $this->createMock(RestaurantRepository::class);
//        $this->em->method('getRepository')->willReturn($restaurantRepository);
//
//        $this->restaurantBuilder
//            ->expects($this->once())
//            ->method('buildRestaurant')
//            ->willReturn($restaurant);
//
//        $this->em
//            ->expects($this->never())
//            ->method('find');
//
//        $restaurant = $this->restaurantProvider->getRestaurant(1);
//        $this->assertInstanceOf(Restaurant::class, $restaurant);
//    }

    public function testRestaurantFileExist(): void
    {
        $restaurantId = 111;
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, $restaurantId);
        }

        $restaurantRepository = $this->createMock(RestaurantRepository::class);
        $restaurant = $this->createMock(Restaurant::class);

        $this->restaurantBuilder
            ->expects($this->never());

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Restaurant::class))
            ->willReturn($restaurantRepository);

        $restaurantRepository
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo($restaurantId))
            ->willReturn($restaurant);

        $restaurant = $this->restaurantProvider->getRestaurant();
        $this->assertInstanceOf(Restaurant::class, $restaurant);
    }

}