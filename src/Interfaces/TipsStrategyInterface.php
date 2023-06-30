<?php

namespace App\Interfaces;

use App\Entity\Order;
use App\Entity\Restaurant;

interface TipsStrategyInterface
{
    public function splitTips(Order $order):void;

}