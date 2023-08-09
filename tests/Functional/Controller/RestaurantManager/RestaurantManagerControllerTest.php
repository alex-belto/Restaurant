<?php

namespace App\Tests\Functional\Controller\RestaurantManager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestaurantManagerControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->getRequest()->doRequest();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
