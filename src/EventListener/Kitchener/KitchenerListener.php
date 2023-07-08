<?php

namespace App\EventListener\Kitchener;

use App\Entity\Order;
use App\Services\Kitchener\KitchenerOrderProcessor;

/**
 * Listening to the order, after we update its status to "READY_TO_KITCHEN" and proceed with the processing.
 */
class KitchenerListener
{
    private KitchenerOrderProcessor $kitchenerOrderProcessor;

    /**
     * @param KitchenerOrderProcessor $kitchenerOrderProcessor
     */
    public function __construct(
        KitchenerOrderProcessor $kitchenerOrderProcessor
    ) {
        $this->kitchenerOrderProcessor = $kitchenerOrderProcessor;
    }

    /**
     * @throws \Exception
     */
    public function processingOrderByKitchen(Order $order) {

        if ($order->getStatus() === Order::READY_TO_KITCHEN) {
            $this->kitchenerOrderProcessor->processingOrder($order);
        }
    }

}