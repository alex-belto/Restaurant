<?php

namespace App\Services\Restaurant;

use App\Entity\Restaurant;
use App\Repository\ClientRepository;
use App\Services\Client\ClientFactory;

/**
 * Responsible for executing the overall logic and operations of a restaurant.
 */
class RestaurantManager
{
    private ClientFactory $clientFactory;
    private ClientRepository $clientRepository;

    public function __construct(
        ClientFactory $clientFactory,
        ClientRepository $clientRepository
    ) {
        $this->clientFactory = $clientFactory;
        $this->clientRepository = $clientRepository;
    }

    /**
     * @throws \Exception
     */
    public function startRestaurant(Restaurant $restaurant): array
    {
        $days = $restaurant->getDays();
        $visitorsForAllTime = 0;

        for ($i = 1; $i <= $days; $i++) {
            $visitorsPerDay = rand(10, 40);
            $visitorsForAllTime += $visitorsPerDay;

            for ($j = 1; $j <= $visitorsPerDay; $j++) {
                $this->clientFactory->createClient(true);
            }
        }

        $visitorsWithTips = $this->clientRepository->getAmountOfClientsWithTips();

        $waiterBalance = [];
        foreach ($restaurant->getWaiters() as $waiter) {
            $waiterBalance[] = [
                'waiter_name' => $waiter->getName(),
                'waiter_balance' => $waiter->getTips()
            ];
        }

        $kitchenersBalance = [];
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
            'visitors_for_all_time' => $visitorsForAllTime,
            'visitors_with_tips' => $visitorsWithTips
        ];
    }
}