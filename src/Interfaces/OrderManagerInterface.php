<?php

namespace App\Interfaces;

use App\Entity\Client;
use App\Entity\Order;

/**
 * Defines methods for processing orders.
 */
interface OrderManagerInterface
{
    public function processingOrder(Order $order);
}