<?php

namespace App\DataFixtures;

use App\Services\Staff\StaffManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * A class for creating staff members.
 */
class StaffFixtures extends Fixture
{
    /**
     * @var StaffManager
     */
    private $staffManager;

    /**
     * @param StaffManager $staffManager
     */
    public function __construct(StaffManager $staffManager) {
        $this->staffManager = $staffManager;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->staffManager->createStaff('waiter');
        }

        for ($i = 1; $i <= 3; $i++) {
            $this->staffManager->createStaff('kitchener');
        }

    }

}