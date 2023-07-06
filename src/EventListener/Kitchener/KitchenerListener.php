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
    private $kitchenerManager;

    /**
     * @param KitchenerManager $kitchenerManager
     */
    public function __construct(
        KitchenerManager $kitchenerManager
    ) {
        $this->kitchenerManager = $kitchenerManager;
    }

    public function processingOrderByKitchen(Order $order) {

        if ($order->getStatus() === Order::READY_TO_KITCHEN) {
            $this->kitchenerManager->processingOrder($order);
        }
    }

}