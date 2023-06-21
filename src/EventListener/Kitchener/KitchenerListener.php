<?php

namespace App\EventListener\Kitchener;

use App\Entity\Order;
use App\Entity\Waiter;
use App\Services\Kitchener\KitchenerManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

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

    public function postUpdateWaiter(LifecycleEventArgs $eventArgs) {

        /** @var Waiter $entity */
        $entity = $eventArgs->getObject();
        $changeSet = $eventArgs->getObjectManager()->getUnitOfWork()->getEntityChangeSet($entity);

        if (isset($changeSet['orders']) &&
            count($changeSet['orders'][0]) < count($changeSet['orders'][1])) {
            /** @var Order $addedOrder */
            $addedOrder = array_diff($changeSet['orders'][1], $changeSet['orders'][0])[0];
            $this->kitchenerService->processingOrder($addedOrder);
        }

    }

}