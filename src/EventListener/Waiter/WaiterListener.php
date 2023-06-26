<?php

namespace App\EventListener\Waiter;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\Checker\Checker;
use App\Services\Waiter\WaiterManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class WaiterListener
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
     * @var Checker
     */
    private $checker;

    /**
     * @param WaiterManager $waiterManager
     * @param EntityManagerInterface $em
     * @param Checker $checker
     */
    public function __construct(
        WaiterManager $waiterManager,
        EntityManagerInterface $em,
        Checker $checker
    ) {
        $this->waiterManager = $waiterManager;
        $this->em = $em;
        $this->checker = $checker;
    }

    public function postUpdateClient(Client $client, LifecycleEventArgs $eventArgs): void
    {
        $changeSet = $eventArgs->getObjectManager()->getUnitOfWork()->getEntityChangeSet($client);
        $isChangedStatus = $this->checker->isChanged($changeSet, ['status']);

        if ($isChangedStatus) {
            $this->waiterManager->processingOrder($client->getConnectedOrder());
        }
    }

    public function postUpdateOrder(Order $order) {

        if ($order->getStatus() === Order::READY_TO_WAITER) {
            $order->setStatus(Order::READY_TO_EAT);
            $kitchener = $order->getKitchener();
            $kitchener->removeOrder($order);
            $this->waiterManager->bringFood($order);
            $this->em->flush();
        }
    }
}