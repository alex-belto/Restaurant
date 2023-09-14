<?php

namespace App\Tests\Unit\Services\Client;

use App\Entity\Client;
use App\Services\Client\ClientFactory;
use ArrayIterator;
use DateTime;
use PHPUnit\Framework\TestCase;

class ClientFactoryTest extends TestCase
{
    public function testCreateClientWithoutCard(): void
    {
        $paymentMethods = new ArrayIterator([
            'cashPayment' => 'CashPaymentProcessor',
        ]);
        $clientFactory = new ClientFactory($paymentMethods);
        $client = $clientFactory->createClient();

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

        $paymentMethods = new ArrayIterator([
            'cardPayment' => 'CardPaymentProcessor',
        ]);
        $clientFactory = new ClientFactory($paymentMethods);
        $client = $clientFactory->createClient();

        $this->assertInstanceOf(DateTime::class, $client->getCardExpirationDate());
        $this->assertIsString($client->getCardNumber());
        $this->assertIsInt($client->getCardCvv());
        $this->assertIsString($client->getName());
        $this->assertIsString($client->getPaymentMethod());
        $this->assertIsFloat($client->getMoney());
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testPaymentMethodException(): void
    {
        $paymentMethods = new ArrayIterator();
        $clientFactory = new ClientFactory($paymentMethods);

        $this->expectExceptionMessage('Payment method not found!');
        $clientFactory->createClient();
    }
}