<?php

namespace App\Services\Tips;

use App\Entity\Kitchener;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Interfaces\TipsStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;

class TipsStandardStrategy implements TipsStrategyInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function splitTips(float $tips, Restaurant $restaurant): void
    {
        $waiters = $restaurant->getWaiters();
        $kitcheners = $restaurant->getKitcheners();
        $amountOfStaff = count($waiters) + count($kitcheners);
        $tipsForEach = $tips / $amountOfStaff;

        /** @var Kitchener $kitchener */
        foreach ($kitcheners as $kitchener) {
            $kitchenerTips = $kitchener->getTips() + $tipsForEach;
            $kitchener->setTips($kitchenerTips);
        }
        $this->em->flush();

        /** @var Waiter $waiter */
        foreach ($waiters as $waiter) {
            $waiterTips = $waiter->getTips() + $tipsForEach;
            $waiter->setTips($waiterTips);
        }
        $this->em->flush();

    }
}