<?php

namespace App\Services\Staff;

use App\Entity\Kitchener;
use App\Entity\Waiter;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class StaffFactory
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function createWaiter(): void
    {
        $faker = Factory::create();
        $waiter = new Waiter();
        $waiter->setName($faker->name());
        $this->em->persist($waiter);
        $this->em->flush();
    }

    public function createKitchener(): void
    {
        $faker = Factory::create();
        $kitchener = new Kitchener();
        $kitchener->setName($faker->name());
        $this->em->persist($kitchener);
        $this->em->flush();
    }


}