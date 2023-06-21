<?php

namespace App\Services\Staff;

use App\Interfaces\StaffInterface;
use App\Repository\KitchenerRepository;
use App\Repository\WaiterRepository;

class ChooseStaff
{
    /**
     * @var WaiterRepository
     */
    private $waiterRepository;

    /**
     * @var KitchenerRepository
     */
    private $kitchenerRepository;

    public function __construct(
        WaiterRepository $waiterRepository,
        KitchenerRepository $kitchenerRepository
    ) {
        $this->waiterRepository = $waiterRepository;
        $this->kitchenerRepository = $kitchenerRepository;
    }

    /**
     * @throws \Exception
     */
    public function chooseStaff(string $type): StaffInterface
    {
        $repository = match ($type) {
            'waiter' => $this->waiterRepository,
            'kitchener' => $this->kitchenerRepository,
            default => throw new \Exception('wrong type' . $type)
        };

        $amountOfStaff = count($repository->findAll());
        $randomStaff = rand(1, $amountOfStaff) - 1;
        return $repository->find(['id' => $randomStaff]);

    }
}