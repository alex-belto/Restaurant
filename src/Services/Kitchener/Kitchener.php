<?php

namespace App\Services\Kitchener;

use App\Entity\Order;
use App\Interfaces\OrderManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class Kitchener implements OrderManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    public function processingOrder(Order $order)
    {
        $order->setStatus(Order::READY_TO_EAT);
        $this->em->flush();
    }

}