<?php

namespace App\Services\Staff;

use App\Entity\Kitchener;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Interfaces\StaffInterface;
use App\Repository\KitchenerRepository;
use App\Repository\RestaurantRepository;
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
     * @var RestaurantRepository
     */
    private $restaurantRepository;

    /**
     * @param WaiterRepository $waiterRepository
     * @param KitchenerRepository $kitchenerRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        WaiterRepository $waiterRepository,
        KitchenerRepository $kitchenerRepository,
        EntityManagerInterface $em,
        RestaurantRepository $restaurantRepository
    ) {
        $this->waiterRepository = $waiterRepository;
        $this->kitchenerRepository = $kitchenerRepository;
        $this->em = $em;
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * @throws \Exception
     */
    public function chooseStaff(string $type): StaffInterface
    {
        $restaurant = Restaurant::getInstance();

        $staff = match ($type) {
            'waiter' => $restaurant->getWaiters(),
            'kitchener' => $restaurant->getKitcheners(),
            default => throw new \Exception('wrong type' . $type)
        };
        
        $amountOfStaff = count($staff);
        $randomStaff = rand(0, $amountOfStaff - 1);
        return $staff[$randomStaff];

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