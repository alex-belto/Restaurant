<?php

namespace App\Interfaces;

use App\Entity\Order;
use App\Entity\Restaurant;

/**
 * The TipStrategy interface defines methods for processing the distribution of tips among staff members.
 */
interface TipsStrategyInterface
{
    public function splitTips(Order $order):void;

}