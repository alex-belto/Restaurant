<?php

namespace App\Services\Client;

use App\Entity\Client;
use App\Entity\Order;
use App\Repository\MenuItemRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class ClientManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MenuItemRepository
     */
    private $menuItemRepository;

    public function __construct(
        EntityManagerInterface $em,
        MenuItemRepository $menuItemRepository
    ) {
        $this->em = $em;
        $this->menuItemRepository = $menuItemRepository;
    }

    public function addClient(bool $card = false): Client
    {
        /** @var EntityManagerInterface $em */
        $em = EntityManagerInterface::class;
        $faker = Factory::create();
        $cardExpirationString = $faker->dateTimeBetween('-6 month', '+2 year')->format('Y-m-d');
        $cardExpiration = DateTime::CreateFromFormat('Y-m-d', $cardExpirationString);

        $client = new Client();
        $client->setName($faker->name());
        $client->setMoney(rand(20, 150));
        if ($card) {
            $client->setCardNumber(rand(2910000000000000, 4800000000000000));
            $client->setCardExpirationDate($cardExpiration);
            $client->setCardCvv(rand(001, 999));
        }
        $em->persist($client);
        $em->flush();

        return $client;
    }

    public function makeOrder(Client $client): Order
    {
        $order = new Order();
        $order->setClient($client);
        $order->setStatus(Order::READY_TO_KITCHEN);

        for ($i = 0; $i <= 5; $i++) {
            $itemId = rand(1, 19);
            $menuItem = $this->menuItemRepository->find(['id' => $itemId]);
            $order->addMenuItem($menuItem);
            $this->em->persist($order);
            $this->em->flush();
        }

        $client->setConnectedOrder($order);
        $this->em->flush();
        return $order;
    }

}