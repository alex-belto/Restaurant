<?php

namespace App\Services\Client;

use App\Entity\Client;
use DateTime;
use Faker\Factory;

/**
 * Class that creates client instances for the restaurant system.
 */
class ClientFactory
{
    private \Traversable $paymentMethods;

    public function __construct(\Traversable $paymentMethods) {
        $this->paymentMethods = $paymentMethods;
    }

    public function createClient(bool $card = false): Client
    {
        $faker = Factory::create();
        $client = new Client();
        $client->setName($faker->name());
        $client->setMoney(rand(100, 150));
        $client->setPaymentMethod($this->getPaymentMethod());
        if ($card) {
            $cardExpirationString = $faker->dateTimeBetween('-6 month', '+2 year')->format('Y-m-d');
            $cardExpiration = DateTime::CreateFromFormat('Y-m-d', $cardExpirationString);
            $cardNumber = rand(2910000000000000, 4800000000000000);
            $client->setCardNumber((string)$cardNumber);
            $client->setCardExpirationDate($cardExpiration);
            $client->setCardCvv(rand(001, 999));
        }

        return $client;
    }

    private function getPaymentMethod(): string
    {
        $amountOfPaymentMethods = iterator_count($this->paymentMethods);
        $randomMethodNumber = rand(1,$amountOfPaymentMethods);

        $countProcessedItems = 1;

        foreach ($this->paymentMethods as $key => $paymentMethod) {
            if($randomMethodNumber === $countProcessedItems) {
                return $key;
            }
            $countProcessedItems++;
        }

        throw new \Exception('Payment method not found!');
    }
}