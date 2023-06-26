<?php

namespace App\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\MenuItem;
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
        EntityManagerInterface $em
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
                $client = $this->clientManager->addClient(true);
                $order = $this->clientManager->makeOrder($client, $restaurant);
                if ($order->getStatus() === Order::DONE) {
                    $order->setTips(rand(5, 20));
                    $this->payOrder->payOrder($client);
                }
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

    /**
     * @throws \Exception
     */
    public function buildRestaurant(int $days): Restaurant
    {
        $restaurant = Restaurant::getInstance();

        if ($restaurant->getDays() === 0) {
            $this->hireStaff($restaurant, 3, 'kitchener');
            $this->hireStaff($restaurant, 7, 'waiter');
            $this->fillUpMenu($restaurant, 15, 'dish');
            $this->fillUpMenu($restaurant,  4, 'drink');
            $restaurant->setDays($days);
            $this->em->persist($restaurant);
            $this->em->flush();
        }

        return $restaurant;
    }

    /**
     * @throws \Exception
     */
    public function hireStaff(Restaurant $restaurant, int $amount, string $type): void
    {
        switch ($type) {
            case 'waiter':
                $waiters = $this->em->getRepository(Waiter::class)->findAll();
                if (count($waiters) >= $amount) {
                    for ($i = 0; $i < $amount; $i++) {
                        $restaurant->addWaiter($waiters[$i]);
                    }
                } else {
                    throw new \Exception('U dont have enough staff in pull');
                }
                break;
            case 'kitchener':
                $kitcheners = $this->em->getRepository(Kitchener::class)->findAll();
                if (count($kitcheners) >= $amount) {
                    for ($i = 0; $i < $amount; $i++) {
                        $restaurant->addKitchener($kitcheners[$i]);
                    }
                } else {
                    throw new \Exception('U dont have enough staff in pull');
                }
                break;
            default:
                throw new \Exception('Wrong stuff type!');
        }
    }

    /**
     * @throws \Exception
     */
    public function fillUpMenu(Restaurant $restaurant, int $amount, string $type): void
    {
        switch ($type) {
            case 'dish':
                $dish = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItem::DISH]);
                if (count($dish) >= $amount) {
                    for ($i = 0; $i < $amount; $i++) {
                        $restaurant->addMenuItem($dish[$i]);
                    }
                } else {
                    throw new \Exception('U dont have enough dish in pull');
                }
                break;
            case 'drink':
                $drink = $this->em->getRepository(MenuItem::class)->findBy(['type' => MenuItem::DRINK]);
                if (count($drink) >= $amount) {
                    for ($i = 0; $i < $amount; $i++) {
                        $restaurant->addMenuItem($drink[$i]);
                    }
                } else {
                    throw new \Exception('U dont have enough drink in pull');
                }
                break;
            default:
                throw new \Exception('Wrong menuItem type!');
        }
    }
}