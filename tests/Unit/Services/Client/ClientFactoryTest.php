<?php

namespace App\Tests\Unit\Services\Client;

use App\Entity\Client;
use App\Services\Client\ClientFactory;
use ArrayIterator;
use DateTime;
use PHPUnit\Framework\TestCase;
use Traversable;

class ClientFactoryTest extends TestCase
{
    private Traversable $paymentMethods;
    private ClientFactory $clientFactory;

    public function setUp(): void
    {
        $this->paymentMethods = new ArrayIterator([
            'cashPayment' => 'CashPaymentProcessor',
            'cardPayment' => 'CardPaymentProcessor'
        ]);
        $this->clientFactory = new ClientFactory($this->paymentMethods);
    }

    public function testCreateClientWithoutCard(): void
    {
        $client = $this->clientFactory->createClient(false);

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
        $client = $this->clientFactory->createClient(true);

        $this->assertInstanceOf(DateTime::class ,$client->getCardExpirationDate());
        $this->assertIsString($client->getCardNumber());
        $this->assertIsInt($client->getCardCvv());
        $this->assertIsString($client->getName());
        $this->assertIsString($client->getPaymentMethod());
        $this->assertIsFloat($client->getMoney());
        $this->assertInstanceOf(Client::class, $client);
    }
}