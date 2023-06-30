<?php

namespace App\Interfaces;

use App\Entity\Client;
use App\Entity\Order;

/**
 * The Order interface defines methods for processing orders.
 */
interface OrderManagerInterface
{
    public function processingOrder(Order $order);
}