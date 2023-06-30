<?php

namespace App\Services\Staff;

use App\Entity\Kitchener;
use App\Entity\Waiter;
use App\Interfaces\StaffInterface;
use App\Services\Restaurant\BuildRestaurant;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class StaffManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var BuildRestaurant
     */
    private $buildRestaurant;

    /**
     * @param EntityManagerInterface $em
     * @param BuildRestaurant $buildRestaurant
     */
    public function __construct(
        EntityManagerInterface $em,
        BuildRestaurant $buildRestaurant
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

        $staff = match ($type) {
            'waiter' => $restaurant->getWaiters(),
            'kitchener' => $restaurant->getKitcheners(),
            default => throw new \Exception('wrong type' . $type)
        };
        
        $amountOfStaff = count($staff);
        $randomStaff = rand(0, $amountOfStaff - 1);
        return $staff[$randomStaff];

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