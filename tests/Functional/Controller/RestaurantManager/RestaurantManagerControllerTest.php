<?php

namespace App\Tests\Functional\Controller\RestaurantManager;

use App\Tests\Functional\Controller\RestaurantManager\DataFixtures\TestRestaurantFixture;
use Doctrine\Common\DataFixtures\Loader;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestaurantManagerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private string $restaurantFilePath;
    private AbstractDatabaseTool $databaseTool;
    private ?Loader $loader;

    static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->restaurantFilePath = $this->getContainer()->getParameter('app.restaurant_file_path');
        if (file_exists($this->restaurantFilePath)) {
            unlink($this->restaurantFilePath);
        }
        $this->loader = static::getContainer()->get('doctrine.fixtures.loader');
    }

    public function tearDown(): void
    {
        if (file_exists($this->restaurantFilePath)) {
            unlink($this->restaurantFilePath);
        }
        parent::tearDown();
    }

    public function testOpenRestaurant(): void
    {
        $this->client->request('GET', '/restaurant/open/1');
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        $days = $data['days'];
        $restaurantBalance = $data['restaurant_balance'];
        $waitersBalance = $data['waiters_balance'];
        $kitchenersBalance = $data['kitcheners_balance'];
        $visitorsForAllTime = $data['visitors_for_all_time'];
        $visitorsWithTips = $data['visitors_with_tips'];
        $amountOfWaitersBalance = 0;
        foreach ($data['waiters_balance'] as $waiter) {
            $amountOfWaitersBalance += $waiter['waiter_balance'];
        }

        $amountOfKitchenersBalance = 0;
        foreach ($data['kitcheners_balance'] as $kitchener) {
            $amountOfKitchenersBalance += $kitchener['kitchener_balance'];
        }

        $this->assertResponseIsSuccessful();
        $this->assertFileExists($this->restaurantFilePath);
        $this->assertIsArray($waitersBalance);
        $this->assertIsArray($kitchenersBalance);
        $this->assertIsInt($days);
        $this->assertIsInt($restaurantBalance);
        $this->assertIsInt($visitorsForAllTime);
        $this->assertIsInt($visitorsWithTips);
        $this->assertEquals(1, $days);
        $this->assertLessThanOrEqual(18000, $restaurantBalance);
        $this->assertGreaterThanOrEqual(30, $restaurantBalance);
        $this->assertLessThanOrEqual(400, $visitorsForAllTime);
        $this->assertGreaterThanOrEqual(10, $visitorsForAllTime);
        $this->assertLessThanOrEqual(400, $visitorsWithTips);
        $this->assertGreaterThanOrEqual(0, $visitorsWithTips);
        $this->assertLessThanOrEqual(2160, $amountOfWaitersBalance);
        $this->assertGreaterThanOrEqual(0, $amountOfWaitersBalance);
        $this->assertLessThanOrEqual(1080, $amountOfKitchenersBalance);
        $this->assertGreaterThanOrEqual(0, $amountOfKitchenersBalance);
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
        $this->loader->addFixture(new TestRestaurantFixture());
        $this->databaseTool->loadFixtures([TestRestaurantFixture::class]);
        $this->client->request('GET', '/restaurant/close');
        $content = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Restaurant closed!', $content);
    }
}
