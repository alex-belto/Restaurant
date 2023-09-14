<?php

namespace App\Tests\Functional\Controller\RestaurantManager\DataFixtures;

use App\Entity\Restaurant;
use App\Enum\RestaurantTipsStrategy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestRestaurantFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $restaurant = new Restaurant();
        $restaurant->setDays(3);
        $restaurant->setBalance(123);
        $restaurant->setTipsStrategy(RestaurantTipsStrategy::TIPS_STANDARD_STRATEGY);
        $manager->persist($restaurant);
        $manager->flush();

        file_put_contents('/var/www/app/tests/restaurant.txt', $restaurant->getId());
    }
}