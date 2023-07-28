<?php

namespace App\Services\Restaurant;

use App\Entity\Restaurant;
use App\Services\Menu\RestaurantMenuFiller;
use App\Services\Staff\EmployeeRecruiter;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Constructs a restaurant by hiring staff,
 * creating a menu for the restaurant, and retrieving the restaurant object.
 */
class RestaurantBuilder
{
    private  RestaurantMenuFiller $restaurantMenuFiller;
    private EmployeeRecruiter $employeeRecruiter;

    public function __construct(
        RestaurantMenuFiller $restaurantMenuFiller,
        EmployeeRecruiter $employeeRecruiter
    ) {
        $this->restaurantMenuFiller = $restaurantMenuFiller;
        $this->employeeRecruiter = $employeeRecruiter;
    }

    /**
     * @throws \Exception
     */
    public function buildRestaurant(int $days): Restaurant
    {
        $restaurant = new Restaurant();

        $this->employeeRecruiter->hireKitcheners($restaurant, 3);
        $this->employeeRecruiter->hireWaiters($restaurant, 7);
        $this->restaurantMenuFiller->fillUpMenu($restaurant, 15, 'dish');
        $this->restaurantMenuFiller->fillUpMenu($restaurant,  4, 'drink');
        $restaurant->setDays($days);

        return $restaurant;
    }
}