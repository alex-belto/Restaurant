<?php

namespace App\Services\Restaurant;

use App\Entity\Restaurant;
use App\Repository\ClientRepository;
use App\Services\Client\ClientFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Responsible for executing the overall logic and operations of a restaurant.
 */
class RestaurantManager
{
    private ClientFactory $clientFactory;
    private ClientRepository $clientRepository;
    private EntityManagerInterface $em;

    public function __construct(
        ClientFactory $clientFactory,
        ClientRepository $clientRepository,
        EntityManagerInterface $em
    ) {
        $this->clientFactory = $clientFactory;
        $this->clientRepository = $clientRepository;
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    public function startRestaurant(Restaurant $restaurant, int $days): array
    {
        $restaurantDays = $restaurant->getDays() + $days;
        $restaurant->setDays($restaurantDays);
        $visitorsForAllTime = $restaurant->getVisitorsForAllTime();

        for ($i = 1; $i <= $days; $i++) {
            $visitorsPerDay = rand(10, 20);
            $visitorsForAllTime += $visitorsPerDay;

            for ($j = 0; $j < $visitorsPerDay; $j++) {
                $client = $this->clientFactory->createClient();
                $this->em->persist($client);
            }
        }
        $restaurant->setVisitorsForAllTime($visitorsForAllTime);
        $this->em->flush();

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
            'days' => $restaurantDays,
            'restaurant_balance' => $restaurant->getBalance(),
            'waiters_balance' => $waiterBalance,
            'kitcheners_balance' => $kitchenersBalance,
            'visitors_for_all_time' => $visitorsForAllTime,
            'visitors_with_tips' => $visitorsWithTips
        ];
    }
}