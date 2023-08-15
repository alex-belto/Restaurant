<?php

namespace App\DataFixtures;

use App\Services\Staff\StaffFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Creating staff members.
 */
class StaffFixture extends Fixture
{
    private StaffFactory $staffFactory;
    private EntityManagerInterface $em;

    public function __construct(
        StaffFactory $staffFactory,
        EntityManagerInterface $em
    ) {
        $this->staffFactory = $staffFactory;
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 7; $i++) {
            $waiter = $this->staffFactory->createWaiter();
            $this->em->persist($waiter);
        }

        for ($i = 1; $i <= 3; $i++) {
            $kitchener = $this->staffFactory->createKitchener();
            $this->em->persist($kitchener);
        }

        $this->em->flush();

    }

}