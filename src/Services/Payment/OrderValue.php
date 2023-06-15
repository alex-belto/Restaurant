<?php

namespace App\Services\Payment;

use App\Entity\Client;
use App\Entity\MenuItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderValue extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @param Client $client
     * @return float
     */
    public function getOrderValue(Client $client): float
    {
        $orderItems = $client->getMenuItems();
        $orderPrice = 0;

        /** @var MenuItem $orderItem */
        foreach ($orderItems as $orderItem)
        {
            $price = $orderItem->getPrice();
            $orderPrice += $price;
        }

        return $orderPrice;
    }

    public function paymentProcess(Client $client): JsonResponse
    {
        $orderValue = $this->getOrderValue($client);
        $customersMoney = $client->getMoney();

        if ($customersMoney > $orderValue)
        {
            $customersMoney -= $orderValue;
            $client->setMoney($customersMoney);
            $this->em->flush();
        } else {
            return $this->json(['message' => 'Customer dont have enough money!']);
        }

        return $this->json(['message' => 'successfully paid with card!']);
    }

}