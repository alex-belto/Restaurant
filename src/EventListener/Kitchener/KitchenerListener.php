<?php

namespace App\EventListener\Kitchener;

use App\Entity\Order;
use App\Services\Kitchener\KitchenerManager;

class KitchenerListener
{
    /**
     * @var KitchenerManager
     */
    private $kitchenerService;

    /**
     * @param KitchenerManager $kitchenerService
     */
    public function __construct(
        KitchenerManager $kitchenerService
    ) {
        $this->kitchenerService = $kitchenerService;
    }

    public function postUpdateOrder(Order $order) {

        if ($order->getStatus() === Order::READY_TO_KITCHEN) {
            $this->kitchenerService->processingOrder($order);
        }
    }

}