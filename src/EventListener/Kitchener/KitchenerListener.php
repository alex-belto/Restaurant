<?php

namespace App\EventListener\Kitchener;

use App\Entity\Order;
use App\Services\Kitchener\KitchenerManager;

/**
 * Listening to the order, after we update its status to "READY_TO_KITCHEN" and proceed with the processing.
 */
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

    public function processingOrderByKitchen(Order $order) {

        if ($order->getStatus() === Order::READY_TO_KITCHEN) {
            $this->kitchenerService->processingOrder($order);
        }
    }

}