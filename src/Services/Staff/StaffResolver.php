<?php

namespace App\Services\Staff;

use App\Interfaces\StaffInterface;
use App\Services\Restaurant\RestaurantProvider;

/**
 * Responsible for choose staff to restaurant.
 */
class StaffResolver
{
    private RestaurantProvider $restaurantProvider;

    public function __construct(
        RestaurantProvider $restaurantProvider
    ) {
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
        $randomStaffIdx = rand(0, $amountOfStaffs - 1);
        return $staffs[$randomStaffIdx];
    }
}