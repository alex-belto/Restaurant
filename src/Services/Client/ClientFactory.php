<?php

namespace App\Services\Client;

use App\Entity\Client;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class ClientFactory
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function createClient(bool $card = false): Client
    {
        $faker = Factory::create();
        $cardExpirationString = $faker->dateTimeBetween('-6 month', '+2 year')->format('Y-m-d');
        $cardExpiration = DateTime::CreateFromFormat('Y-m-d', $cardExpirationString);

        $client = new Client();
        $client->setName($faker->name());
        $client->setMoney(rand(100, 150));
        if ($card) {
            $cardNumber = rand(2910000000000000, 4800000000000000);
            $client->setCardNumber("$cardNumber");
            $client->setCardExpirationDate($cardExpiration);
            $client->setCardCvv(rand(001, 999));
        }
        $this->em->persist($client);
        $this->em->flush();

        return $client;
    }
}