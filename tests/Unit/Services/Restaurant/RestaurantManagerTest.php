<?php

namespace App\Tests\Unit\Services\Restaurant;

use App\Entity\Client;
use App\Entity\Restaurant;
use App\Repository\ClientRepository;
use App\Services\Client\ClientFactory;
use App\Services\Restaurant\RestaurantManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class RestaurantManagerTest extends TestCase
{
    public function testRestaurantManager(): void
    {
        $days = 3;
        $clientFactory = $this->createMock(ClientFactory::class);
        $clientRepository = $this->createMock(ClientRepository::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $client = $this->createMock(Client::class);
        $restaurant = $this->createMock(Restaurant::class);

        $restaurant
            ->expects($this->once())
            ->method('getDays')
            ->willReturn($days);

        $clientFactory
            ->expects($this->exactly(30))
            ->method('createClient')
            ->willReturn($client);

        $em
            ->expects($this->exactly(30))
            ->method('persist')
            ->with($this->equalTo($client));

        $em
            ->expects($this->exactly(30))
            ->method('flush');

        $clientRepository
            ->expects($this->once())
            ->method('getAmountOfClientsWithTips')
            ->willReturn(20);

        $restaurantManager = new RestaurantManager($clientFactory, $clientRepository, $em);
        $result = $restaurantManager->startRestaurant($restaurant);
        $this->assertIsArray($result);
        $this->assertEquals(20, $result['visitors_with_tips']);
    }
}