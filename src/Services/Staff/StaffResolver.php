<?php

namespace App\Services\Staff;

use App\Entity\Kitchener;
use App\Entity\Waiter;
use App\Interfaces\StaffInterface;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

/**
 * Responsible for creating staff and choose staff to restaurant.
 */
class StaffResolver
{
    private EntityManagerInterface $em;

    private RestaurantProvider $restaurantProvider;

    /**
     * @param EntityManagerInterface $em
     * @param RestaurantProvider $restaurantProvider
     */
    public function __construct(
        EntityManagerInterface $em,
        RestaurantProvider $restaurantProvider
    ) {
        $this->em = $em;
        $this->restaurantProvider = $restaurantProvider;
    }

    /**
     * @throws \Exception
     */
    public function chooseStaff(string $type): StaffInterface
    {
        $restaurant = $this->restaurantProvider->getRestaurant();

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