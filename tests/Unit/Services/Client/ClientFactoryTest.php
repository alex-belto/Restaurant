<?php

namespace App\Tests\Unit\Services\Client;

use App\Entity\Client;
use App\Enum\ClientStatus;
use App\Services\Client\ClientFactory;
use ArrayIterator;
use DateTime;
use PHPUnit\Framework\TestCase;
use Traversable;

class ClientFactoryTest extends TestCase
{
    private Traversable $paymentMethods;
    private ClientFactory $clientFactory;
    private Client $client;

    public function setUp(): void
    {
        $this->paymentMethods = new ArrayIterator([
            'cashPayment' => 'CashPaymentProcessor',
            'cardPayment' => 'CardPaymentProcessor'
        ]);
        $this->client = $this->createMock(Client::class);
        $this->mockClientFactory = $this->createMock(ClientFactory::class);
        $this->clientFactory = new ClientFactory($this->paymentMethods);
    }

    public function testCreateClientWithoutCard(): void
    {
        $this->markTestSkipped('in progress');

        $this->mockClientFactory
            ->expects($this->once())
            ->method('getPaymentMethod')
            ->willReturn('cashPayment');

        $this->client
            ->expects($this->once())
            ->method('setName')
            ->with($this->equalTo('Olex'));

        $this->client
            ->expects($this->once())
            ->method('setStatus')
            ->with($this->equalTo(ClientStatus::WITHOUT_ORDER));

        $this->client
            ->expects($this->once())
            ->method('setMoney')
            ->with($this->equalTo(150));

        $client = $this->clientFactory->createClient();

        $this->assertNull($client->getCardExpirationDate());
        $this->assertNull($client->getCardNumber());
        $this->assertNull($client->getCardCvv());
        $this->assertIsString($client->getName());
        $this->assertIsString($client->getPaymentMethod());
        $this->assertIsFloat($client->getMoney());
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testCreateClientWithCard(): void
    {
        $this->markTestSkipped('in progress');

        $date = new DateTime();

        $this->mockClientFactory
            ->expects($this->once())
            ->method('getPaymentMethod')
            ->willReturn('cardPayment');

        $this->client
            ->expects($this->once())
            ->method('setName')
            ->with($this->equalTo('Olex'));

        $this->client
            ->expects($this->once())
            ->method('setStatus')
            ->with($this->equalTo(ClientStatus::WITHOUT_ORDER));

        $this->client
            ->expects($this->once())
            ->method('setMoney')
            ->with($this->equalTo(100));

        $this->client
            ->expects($this->once())
            ->method('setCardNumber')
            ->with($this->equalTo('2910000000000000'));

        $this->client
            ->expects($this->once())
            ->method('setCardExpirationDate')
            ->with($this->equalTo($date->modify('+1 month')));

        $this->client
            ->expects($this->once())
            ->method('setCardCvv')
            ->with($this->equalTo(345));

        $client = $this->clientFactory->createClient();

        $this->assertInstanceOf(DateTime::class, $client->getCardExpirationDate());
        $this->assertIsString($client->getCardNumber());
        $this->assertIsInt($client->getCardCvv());
        $this->assertIsString($client->getName());
        $this->assertIsString($client->getPaymentMethod());
        $this->assertIsFloat($client->getMoney());
        $this->assertInstanceOf(Client::class, $client);
    }
}