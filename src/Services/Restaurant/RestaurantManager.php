<?php

namespace App\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Services\Client\ClientManager;
use App\Services\Payment\PayOrder;
use Doctrine\ORM\EntityManagerInterface;

class RestaurantManager
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * @var PayOrder
     */
    private $payOrder;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param ClientManager $clientManager
     * @param PayOrder $payOrder
     * @param EntityManagerInterface $em
     */
    public function __construct(
        ClientManager $clientManager,
        PayOrder $payOrder,
        EntityManagerInterface $em,
    ) {
        $this->clientManager = $clientManager;
        $this->payOrder = $payOrder;
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    public function startRestaurant(Restaurant $restaurant): array
    {
        $days = $restaurant->getDays();
        $visitorsForAllTime = 0;

        for ($i = 1; $i <= $days; $i++) {
            $visitorsPerDay = rand(50, 400);
            $visitorsForAllTime += $visitorsPerDay;

            for ($j = 1; $j <= $visitorsPerDay; $j++) {
                $this->clientManager->addClient(true);
            }
            $days --;
            $restaurant->setDays($days);
            $this->em->flush();
        }

        $waiterBalance = [];
        /** @var Waiter $waiter */
        foreach ($restaurant->getWaiters() as $waiter) {
            $waiterBalance[] = [
                'waiter_name' => $waiter->getName(),
                'waiter_balance' => $waiter->getTips()
            ];
        }

        $kitchenersBalance = [];
        /** @var Kitchener $kitchener */
        foreach ($restaurant->getKitcheners() as $kitchener) {
            $kitchenersBalance[] = [
                'kitchener_name' => $kitchener->getName(),
                'kitchener_balance' => $kitchener->getTips()
            ];
        }

        return [
            'days' => $days,
            'restaurant_balance' => $restaurant->getBalance(),
            'waiters_balance' => $waiterBalance,
            'kitcheners_balance' => $kitchenersBalance,
            'visitors_for_all_time' => $visitorsForAllTime
        ];
    }
}