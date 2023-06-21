<?php

namespace App\Services\Staff;

use App\Entity\Kitchener;
use App\Entity\Waiter;
use App\Interfaces\StaffInterface;
use App\Repository\KitchenerRepository;
use App\Repository\WaiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class StaffManager
{
    /**
     * @var WaiterRepository
     */
    private $waiterRepository;

    /**
     * @var KitchenerRepository
     */
    private $kitchenerRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param WaiterRepository $waiterRepository
     * @param KitchenerRepository $kitchenerRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        WaiterRepository $waiterRepository,
        KitchenerRepository $kitchenerRepository,
        EntityManagerInterface $em
    ) {
        $this->waiterRepository = $waiterRepository;
        $this->kitchenerRepository = $kitchenerRepository;
        $this->em = $em;
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

    /**
     * @throws \Exception
     */
    public function createStaff(string $type): void
    {
        $faker = Factory::create();
        $staff = match ($type) {
            'waiter' => new Waiter(),
            'kitchener' => new Kitchener(),
            default => throw new \Exception('wrong type' . $type)
        };
        $staff->setName($faker->name());
        $this->em->persist($staff);
        $this->em->flush();
    }
}