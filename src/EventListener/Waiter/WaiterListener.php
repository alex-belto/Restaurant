<?php

namespace App\EventListener\Waiter;

use App\Entity\Client;
use App\Entity\Kitchener;
use App\Entity\Order;
use App\Services\Waiter\WaiterManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class WaiterListener implements EventSubscriber
{
    /**
     * @var WaiterManager
     */
    private $waiterManager;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param WaiterManager $waiterManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        WaiterManager $waiterManager,
        EntityManagerInterface $em
    ) {
        $this->waiterManager = $waiterManager;
        $this->em = $em;
    }

    public function getSubscribedEvents(): array
    {
        return [
            'postUpdateClient',
            'postUpdateKitchener'
        ];
    }

    public function postUpdateClient(LifecycleEventArgs $eventArgs) {

        /** @var Client $entity */
        $entity = $eventArgs->getObject();
        $changeSet = $eventArgs->getObjectManager()->getUnitOfWork()->getEntityChangeSet($entity);

        if (isset($changeSet['order'])) {
            $this->waiterManager->processingOrder($entity->getConnectedOrder());
        }
    }

    public function postUpdateKitchener(LifecycleEventArgs $eventArgs) {

        /** @var Kitchener $entity */
        $entity = $eventArgs->getObject();
        $changeSet = $eventArgs->getObjectManager()->getUnitOfWork()->getEntityChangeSet($entity);

        if (isset($changeSet['orders']) &&
            count($changeSet['orders'][0]) < count($changeSet['orders'][1])) {
            /** @var Order $addedOrder */
            $addedOrder = array_diff($changeSet['orders'][1], $changeSet['orders'][0])[0];
            if ($addedOrder->getStatus() === Order::READY_TO_WAITER) {
                $addedOrder->setStatus(Order::READY_TO_EAT);
                $entity->removeOrder($addedOrder);
                $this->em->flush();
                $this->waiterManager->bringFood($addedOrder);
            }
        }
    }

}