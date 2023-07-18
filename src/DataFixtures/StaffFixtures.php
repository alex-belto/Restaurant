<?php

namespace App\DataFixtures;

use App\Services\Staff\StaffFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Creating staff members.
 */
class StaffFixtures extends Fixture
{
    private StaffFactory $staffFactory;

    public function __construct(StaffFactory $staffFactory) {
        $this->staffFactory = $staffFactory;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->staffFactory->createWaiter();
        }

        for ($i = 1; $i <= 3; $i++) {
            $this->staffFactory->createKitchener();
        }

    }

}