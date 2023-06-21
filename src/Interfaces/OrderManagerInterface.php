<?php

namespace App\Interfaces;

use App\Entity\Client;
use App\Entity\Order;

interface OrderManagerInterface
{
    public function processingOrder(Order $order);
}