<?php

namespace App\Tests\Functional\Services\Restaurant;

use App\Entity\Restaurant;
use App\Services\Restaurant\RestaurantBuilder;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestaurantProviderTest extends WebTestCase
{
    private RestaurantBuilder $restaurantBuilder;
    private EntityManagerInterface $em;
    private RestaurantProvider $restaurantProvider;
    private string $restaurantFilePath;
    private EntityRepository $entityRepository;
    private Restaurant $restaurant;

    public function setUp(): void
    {
        $this->restaurantFilePath = $this->getContainer()->getParameter('app.restaurant_file_path');
        $this->restaurantBuilder = $this->createMock(RestaurantBuilder::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->restaurantProvider = new RestaurantProvider($this->restaurantBuilder, $this->em, $this->restaurantFilePath);
        $this->entityRepository = $this->createMock(EntityRepository::class);
        $this->restaurant = $this->createMock(Restaurant::class);
    }

    public function tearDown(): void
    {
        if (file_exists($this->restaurantFilePath)) {
            unlink($this->restaurantFilePath);
        }
    }

    public function testNotExistRestaurant(): void
    {
        if (file_exists($this->restaurantFilePath)) {
            unlink($this->restaurantFilePath);
        }

        $this->em
            ->expects($this->never())
            ->method('getRepository')
            ->willReturn($this->entityRepository);

        $this->em
            ->expects($this->never())
            ->method('find');

        $restaurant = $this->restaurantProvider->getRestaurant(1);
        $this->assertInstanceOf(Restaurant::class, $restaurant);
    }

    public function testRestaurantFileExist(): void
    {
        $restaurantId = 111;
        file_put_contents($this->restaurantFilePath, $restaurantId);

        $this->restaurantBuilder
            ->expects($this->never())
            ->method('build');

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Restaurant::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo($restaurantId))
            ->willReturn($this->restaurant);

        $restaurant = $this->restaurantProvider->getRestaurant(1);
        $this->assertInstanceOf(Restaurant::class, $restaurant);
    }

    public function testRestaurantFileExistButRestaurantNotFound(): void
    {
        $restaurantId = 111;
        file_put_contents($this->restaurantFilePath, $restaurantId);

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Restaurant::class))
            ->willReturn($this->entityRepository);

       $this->entityRepository
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo($restaurantId))
            ->willReturn(null);

        $restaurant = $this->restaurantProvider->getRestaurant(1);
        $this->assertInstanceOf(Restaurant::class, $restaurant);
    }

}