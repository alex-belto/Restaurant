<?php

namespace App\Services\Staff;

use App\Entity\Kitchener;
use App\Entity\Waiter;
use App\Interfaces\StaffInterface;
use App\Services\Restaurant\RestaurantBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class StaffManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RestaurantBuilder
     */
    private $buildRestaurant;

    /**
     * @param EntityManagerInterface $em
     * @param RestaurantBuilder $buildRestaurant
     */
    public function __construct(
        EntityManagerInterface $em,
        RestaurantBuilder $buildRestaurant
    ) {
        $this->em = $em;
        $this->buildRestaurant = $buildRestaurant;
    }

    /**
     * @throws \Exception
     */
    public function chooseStaff(string $type): StaffInterface
    {
        $restaurant = $this->buildRestaurant->getRestaurant();

        $staffs = match ($type) {
            'waiter' => $restaurant->getWaiters(),
            'kitchener' => $restaurant->getKitcheners(),
            default => throw new \Exception('wrong type' . $type)
        };
        
        $amountOfStaffs = count($staffs);
        $randomStaffId = rand(0, $amountOfStaffs - 1);
        return $staffs[$randomStaffId];
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