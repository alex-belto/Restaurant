<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Client;
use App\Entity\Order;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private Client $client;
    private Order $order;

    public function setUp(): void
    {
        $this->client = new Client();
        $this->order = $this->createMock(Order::class);
    }

    public function testClientHasEnoughMoney(): void
    {
        $this->client->setMoney(200.00);
        $this->client->setConnectedOrder($this->order);

        $this->order
            ->method('getPrice')
            ->willReturn(100.00);
        $this->order
            ->method('getTips')
            ->willReturn(10);

        $this->assertEquals(true, $this->client->isEnoughMoney());
    }

    public function testClientDoesntHaveEnoughMoney(): void
    {
        $this->client->setMoney(100.00);
        $this->client->setConnectedOrder($this->order);

        $this->order
            ->method('getPrice')
            ->willReturn(100.00);
        $this->order
            ->method('getTips')
            ->willReturn(10);

        $this->assertEquals(false, $this->client->isEnoughMoney());
    }

    public function testCardNotValid(): void
    {
        $notValidDate = new \DateTime('-1year');
        $this->client->setCardExpirationDate($notValidDate);

        $this->assertEquals(false, $this->client->isCardValid());
    }

    public function testCardValid(): void
    {
        $notValidDate = new \DateTime('+1year');
        $this->client->setCardExpirationDate($notValidDate);

        $this->assertEquals(true, $this->client->isCardValid());
    }

}