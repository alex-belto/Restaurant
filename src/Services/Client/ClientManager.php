<?php

namespace App\Services\Client;

use App\Entity\Client;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class ClientManager
{
    public function addClient(bool $card = false): void
    {
        /** @var EntityManagerInterface $em */
        $em = EntityManagerInterface::class;
        $faker = Factory::create();
        $cardExpirationString = $faker->dateTimeBetween('-6 month', '+2 year')->format('Y-m-d');
        $cardExpiration = DateTime::CreateFromFormat('Y-m-d', $cardExpirationString);

        $client = new Client();
        $client->setName($faker->name);
        $client->setMoney(rand(20, 150));
        if ($card) {
            $client->setCardNumber(rand(2910000000000000, 4800000000000000));
            $client->setCardExpirationDate($cardExpiration);
            $client->setCardCvv(rand(001, 999));
        }
        $em->persist($client);
        $em->flush();
    }

}