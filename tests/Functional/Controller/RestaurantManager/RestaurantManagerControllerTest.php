<?php

namespace App\Tests\Functional\Controller\RestaurantManager;

use App\Services\Restaurant\RestaurantBuilder;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestaurantManagerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
    private string $filePath;
    private RestaurantBuilder $restaurantBuilder;
    private RestaurantProvider $restaurantProvider;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = $this->getContainer()->get(EntityManagerInterface::class);
        $this->em->getConnection()->beginTransaction();
        $this->restaurantBuilder = new RestaurantBuilder($this->em);
        $this->restaurantProvider = new RestaurantProvider($this->restaurantBuilder, $this->em);
        $this->filePath = realpath(__DIR__ . '/../../../..') . $_ENV['FILE_PATH'];
    }

    public function tearDown(): void
    {
        $this->em->getConnection()->rollBack();
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
        parent::tearDown();
    }

    public function testOpenRestaurant(): void
    {
        $this->client->request('GET', '/restaurant/open/1');
        $this->assertResponseIsSuccessful();
    }

    public function testRestaurantNotFound(): void
    {
        $this->client->request('GET', '/restaurant/close');
        $content = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Restaurant not found!', $content);
    }

    public function testRestaurantClosed(): void
    {
        $this->restaurantProvider->getRestaurant(1);
        $this->client->request('GET', '/restaurant/close');
        $content = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Restaurant closed!', $content);
    }
}
