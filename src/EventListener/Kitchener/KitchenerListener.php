<?php

namespace App\EventListener\Kitchener;

use App\Entity\Kitchener;
use App\Entity\Order;
use App\Services\Staff\StaffResolver;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Listening to the order, after we update its status to "READY_TO_KITCHEN" and proceed with the processing.
 */
class KitchenerListener
{
    private EntityManagerInterface $em;
    private StaffResolver $staffResolver;

    public function __construct(
        EntityManagerInterface $em,
        StaffResolver $staffResolver
    ) {
        $this->em = $em;
        $this->staffResolver = $staffResolver;
    }

    /**
     * @throws \Exception
     */
    public function processOrderByKitchen(Order $order) {

        if ($order->getStatus() !== Order::READY_TO_KITCHEN) {
            return;
        }

        /** @var Kitchener $kitchener */
        $kitchener = $this->staffResolver->chooseStaff('kitchener');
        $kitchener->addOrder($order);
        $order->setStatus(Order::READY_TO_WAITER);
        $this->em->flush();
    }

}