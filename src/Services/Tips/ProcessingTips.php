<?php

namespace App\Services\Tips;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class ProcessingTips
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TipsStandardStrategy
     */
    private $tipsStandardStrategy;

    /**
     * @var TipsWaiterStrategy
     */
    private $tipsWaiterStrategy;

    /**
     * @param EntityManagerInterface $em
     * @param TipsStandardStrategy $tipsStandardStrategy
     * @param TipsWaiterStrategy $tipsWaiterStrategy
     */
    public function __construct(
        EntityManagerInterface $em,
        TipsStandardStrategy $tipsStandardStrategy,
        TipsWaiterStrategy $tipsWaiterStrategy
    ) {
        $this->em = $em;
        $this->tipsStandardStrategy = $tipsStandardStrategy;
        $this->tipsWaiterStrategy = $tipsWaiterStrategy;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Order $order): void
    {
        $restaurant = $order->getWaiter()->getRestaurant();
        $tipsStrategy = match ($restaurant->getTipsStrategy()) {
            1 => $this->tipsStandardStrategy,
            2 => $this->tipsWaiterStrategy
        };
        $tipsStrategy->splitTips($order);
    }
}