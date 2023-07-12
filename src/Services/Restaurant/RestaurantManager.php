<?php

namespace App\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Repository\ClientRepository;
use App\Services\Client\ClientManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Responsible for executing the overall logic and operations of a restaurant.
 */
class RestaurantManager
{
    private ClientManager $clientManager;

    private ClientRepository $clientRepository;

    public function __construct(
        ClientManager $clientManager,
        ClientRepository $clientRepository
    ) {
        $this->clientManager = $clientManager;
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
            $visitorsPerDay = rand(50, 400);
            $visitorsForAllTime += $visitorsPerDay;

            for ($j = 1; $j <= $visitorsPerDay; $j++) {
                $this->clientManager->addClient(true);
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