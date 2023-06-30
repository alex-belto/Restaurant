<?php

namespace App\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Repository\ClientRepository;
use App\Services\Client\ClientManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The class is responsible for executing the overall logic and operations of a restaurant.
 */
class RestaurantManager
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @param ClientManager $clientManager
     * @param EntityManagerInterface $em
     * @param ClientRepository $clientRepository
     */
    public function __construct(
        ClientManager $clientManager,
        EntityManagerInterface $em,
        ClientRepository $clientRepository
    ) {
        $this->clientManager = $clientManager;
        $this->em = $em;
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
            $days --;
            $restaurant->setDays($days);
            $this->em->flush();
        }

        $visitorsWithTips = $this->clientRepository->getAmountOfClientsWithTips();

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
            'visitors_for_all_time' => $visitorsForAllTime,
            'visitors_with_tips' => $visitorsWithTips
        ];
    }
}