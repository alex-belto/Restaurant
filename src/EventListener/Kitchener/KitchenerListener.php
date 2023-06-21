<?php

namespace App\EventListener\Kitchener;

use App\Services\Kitchener\Kitchener;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class KitchenerListener
{
    /**
     * @var Kitchener
     */
    private $kitchenerService;

    public function __construct(Kitchener $kitchenerService) {
        $this->kitchenerService = $kitchenerService;
    }

    public function postUpdateWaiter(LifecycleEventArgs $eventArgs) {

        $entity = $eventArgs->getObject();
        $changeSet = $eventArgs->getObjectManager()->getUnitOfWork()->getEntityChangeSet($entity);
        if (isset($changeSet['orders']) &&
            count($changeSet['orders'][0]) < count($changeSet['orders'])) {
            $addedOrder = array_diff($changeSet['orders'][1], $changeSet['orders'][0]);
            $this->kitchenerService->processingOrder($addedOrder[0]);
        }

    }

}