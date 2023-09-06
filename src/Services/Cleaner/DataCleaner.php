<?php

namespace App\Services\Cleaner;

use App\Entity\Order;
use App\Services\Restaurant\RestaurantProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * A utility class responsible for cleansing data for restaurant operations.
 */
class DataCleaner
{
    private EntityManagerInterface $em;
    private RestaurantProvider $restaurantProvider;

    public function __construct(
        EntityManagerInterface $em,
        RestaurantProvider $restaurantProvider
    ) {
        $this->em = $em;
        $this->restaurantProvider = $restaurantProvider;
    }

    public function removeRestaurantData(): string
    {
        $this->removeRestaurantHistoryData();
        return $this->removeRestaurantFile();
    }

    private function removeRestaurantFile(): string
    {
        $filePath = $this->restaurantProvider->getFilePath();
        if (!file_exists($filePath)) {
            return 'Restaurant not found!';
        }

        unlink($filePath);
        return 'Restaurant closed!';
    }

    private function removeRestaurantHistoryData(): void
    {
        $orders = $this->em->getRepository(Order::class)->findAll();
        foreach ($orders as $order) {
            $this->em->remove($order);
        }
        $this->em->flush();
    }
}