<?php

namespace App\Services\Client;

use App\Entity\Client;
use App\Entity\MenuItem;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Repository\MenuItemRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

/**
 * Responsible for creating clients and managing their orders.
 */
class ClientManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        EntityManagerInterface $em,
    ) {
        $this->em = $em;
    }

    public function addClient(bool $card = false): Client
    {
        $faker = Factory::create();
        $cardExpirationString = $faker->dateTimeBetween('-6 month', '+2 year')->format('Y-m-d');
        $cardExpiration = DateTime::CreateFromFormat('Y-m-d', $cardExpirationString);

        $client = new Client();
        $client->setName($faker->name());
        $client->setMoney(rand(20, 150));
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

    public function makeOrder(Client $client, Restaurant $restaurant): Order
    {
        $order = new Order();
        $order->setClient($client);
        $order->setStatus(Order::READY_TO_KITCHEN);
        $menu = $restaurant->getMenuItems()->toArray();

        for ($i = 0; $i < 5; $i++) {
            $item = rand(0, 18);
            /** @var MenuItem $menuItem */
            $menuItem = $menu[$item];
            $order->addMenuItem($menuItem);
            $this->em->persist($order);
        }

        $client->setStatus(Client::ORDER_PLACED);
        $client->setConnectedOrder($order);
        $this->em->flush();
        return $order;
    }

}