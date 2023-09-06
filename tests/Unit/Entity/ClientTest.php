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
     * @dataProvider dataProviderForTestIsEnoughMoneyForOrder
     */
    public function testIsEnoughMoneyForOrder(
        float $money,
        float $price,
        int $tips,
        bool $expected
    ): void
    {
        $this->client->setMoney($money);
        $this->client->setConnectedOrder($this->order);

        $this->order
            ->expects($this->once())
            ->method('getPrice')
            ->willReturn($price);

        $this->order
            ->expects($this->once())
            ->method('getTips')
            ->willReturn($tips);

        $this->assertEquals($expected, $this->client->isEnoughMoneyForOrder());
    }

    static function dataProviderForTestIsEnoughMoneyForOrder(): array
    {
        return [
            'clientHasEnoughMoney' => [
                'money' => 200.00,
                'price' => 100.00,
                'tips' => 10,
                'expected' => true
            ],
            'clientDoesntHaveEnoughMoney' => [
                'money' => 100.00,
                'price' => 100.00,
                'tips' => 10,
                'expected' => false
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsCardValid
     */
    public function testIsCardValid(\DateTime $dateExpiration, bool $expected): void
    {
        $this->client->setCardExpirationDate($dateExpiration);

        $this->assertEquals($expected, $this->client->isCardValid());
    }

    static function dataProviderForTestIsCardValid(): array
    {
        return [
            'cardValid' => [
                'dateExpiration' => new \DateTime('+1year'),
                'expected' => true
            ],
            'cardNotValid' => [
                'dateExpiration' => new \DateTime('-1year'),
                'expected' => false
            ],
        ];
    }

}