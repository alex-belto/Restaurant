<?php

namespace App\Services\Client;

use App\Entity\Client;
use DateTime;
use Faker\Factory;

class ClientFactory
{
    public function createClient(bool $card = false): Client
    {
        $faker = Factory::create();
        $client = new Client();
        $client->setName($faker->name());
        $client->setMoney(rand(100, 150));
        if ($card) {
            $cardExpirationString = $faker->dateTimeBetween('-6 month', '+2 year')->format('Y-m-d');
            $cardExpiration = DateTime::CreateFromFormat('Y-m-d', $cardExpirationString);
            $cardNumber = rand(2910000000000000, 4800000000000000);
            $client->setCardNumber("$cardNumber");
            $client->setCardExpirationDate($cardExpiration);
            $client->setCardCvv(rand(001, 999));
        }

        return $client;
    }
}