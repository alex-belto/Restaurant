<?php

namespace App\Services\Staff;

use App\Entity\Restaurant;
use App\Repository\KitchenerRepository;
use App\Repository\WaiterRepository;

class EmployeeRecruiter
{
    private WaiterRepository $waiterRepository;
    private KitchenerRepository $kitchenerRepository;

    public function __construct(
        WaiterRepository $waiterRepository,
        KitchenerRepository $kitchenerRepository
    ) {
        $this->waiterRepository = $waiterRepository;
        $this->kitchenerRepository = $kitchenerRepository;
    }

    public function hireWaiters(Restaurant $restaurant, int $amount): void
    {
        $waiters = $this->waiterRepository->findAll();
        if (!count($waiters) >= $amount) {
            throw new \Exception('U dont have enough staff in pull');
        }

        for ($i = 0; $i < $amount; $i++) {
            $restaurant->addWaiter($waiters[$i]);
        }
    }

    public function hireKitcheners(Restaurant $restaurant, int $amount): void
    {
        $kitcheners = $this->kitchenerRepository->findAll();
        if (!count($kitcheners) >= $amount) {
            throw new \Exception('U dont have enough staff in pull');
        }

        for ($i = 0; $i < $amount; $i++) {
            $restaurant->addKitchener($kitcheners[$i]);
        }
    }
}