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

    /**
     * @dataProvider dataProviderForTestIsEnoughMoney
     */
    public function testIsEnoughMoney(
        float $money,
        float $price,
        int $tips,
        bool $expect
    ): void
    {
        $this->client->setMoney($money);
        $this->client->setConnectedOrder($this->order);

        $this->order
            ->method('getPrice')
            ->willReturn($price);
        $this->order
            ->method('getTips')
            ->willReturn($tips);

        $this->assertEquals($expect, $this->client->isEnoughMoney());
    }

    static function dataProviderForTestIsEnoughMoney(): array
    {
        return [
            'clientHasEnoughMoney' => [
                'money' => 200.00,
                'price' => 100.00,
                'tips' => 10,
                'expect' => true
            ],
            'clientDoesntHaveEnoughMoney' => [
                'money' => 100.00,
                'price' => 100.00,
                'tips' => 10,
                'expect' => false
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsCardValid
     */
    public function testIsCardValid(\DateTime $dateExpiration, bool $expect): void
    {
        $this->client->setCardExpirationDate($dateExpiration);

        $this->assertEquals($expect, $this->client->isCardValid());
    }

    static function dataProviderForTestIsCardValid(): array
    {
        return [
            'cardValid' => [
                'dateExpiration' => new \DateTime('+1year'),
                'expect' => true
            ],
            'cardNotValid' => [
                'dateExpiration' => new \DateTime('-1year'),
                'expect' => false
            ],
        ];
    }

}