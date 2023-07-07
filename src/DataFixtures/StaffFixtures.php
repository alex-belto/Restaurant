<?php

namespace App\DataFixtures;

use App\Services\Staff\StaffResolver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Creating staff members.
 */
class StaffFixtures extends Fixture
{
    /**
     * @var StaffResolver
     */
    private $staffResolver;

    /**
     * @param StaffResolver $staffResolver
     */
    public function __construct(StaffResolver $staffResolver) {
        $this->staffResolver = $staffResolver;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->staffResolver->createStaff('waiter');
        }

        for ($i = 1; $i <= 3; $i++) {
            $this->staffResolver->createStaff('kitchener');
        }

    }

}