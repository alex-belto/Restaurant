<?php

namespace App\Services\Tips;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProcessingTips
{
    /**
     * @throws \Exception
     */
    public function __invoke(Order $order): void
    {
        $container = new ContainerBuilder();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);

        $restaurant = $order->getWaiter()->getRestaurant();
        $tipsStrategy = match ($restaurant->getTipsStrategy()) {
            1 => new TipsStandardStrategy($entityManager),
            2 => new TipsWaiterStrategy($entityManager)
        };
        $tipsStrategy->splitTips($order);
    }
}