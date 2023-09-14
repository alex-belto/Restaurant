<?php

namespace App\Services\Staff;

use App\Entity\Kitchener;
use App\Entity\Waiter;
use Faker\Factory;

/**
 * Responsible for creating staff.
 */
class StaffFactory
{
    public function createWaiter(): Waiter
    {
        $faker = Factory::create();
        $waiter = new Waiter();
        $waiter->setName($faker->name());

        return $waiter;
    }

    public function createKitchener(): Kitchener
    {
        $faker = Factory::create();
        $kitchener = new Kitchener();
        $kitchener->setName($faker->name());

        return $kitchener;
    }
}