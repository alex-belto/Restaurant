<?php

namespace App\EventListener\Kitchener;

use App\Entity\Order;
use App\Entity\Waiter;
use App\Services\Checker\Checker;
use App\Services\Kitchener\KitchenerManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class KitchenerListener
{
    /**
     * @var KitchenerManager
     */
    private $kitchenerService;

    /**
     * @var Checker
     */
    private $checker;

    /**
     * @param KitchenerManager $kitchenerService
     * @param Checker $checker
     */
    public function __construct(
        KitchenerManager $kitchenerService,
        Checker $checker
    ) {
        $this->kitchenerService = $kitchenerService;
        $this->checker = $checker;
    }

    public function postUpdateOrder(Order $order) {

        if ($order->getStatus() === Order::READY_TO_KITCHEN) {
            $this->kitchenerService->processingOrder($order);
        }
    }

}