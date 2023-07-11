<?php

namespace App\EventListener\Waiter;

use App\Entity\Client;
use App\Entity\Order;
use App\Services\Waiter\WaiterOrderProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Listening to the client, we pass the order to the chef after its status has been changed.
 * After listening to the order, we pass it to the waiter for processing once its status changes to "READY_TO_WAITER."
 */
class WaiterListener
{
    private WaiterOrderProcessor $waiterOrderProcessor;

    private EntityManagerInterface $em;

    /**
     * @param WaiterOrderProcessor $waiterOrderProcessor
     * @param EntityManagerInterface $em
     */
    public function __construct(
        WaiterOrderProcessor   $waiterOrderProcessor,
        EntityManagerInterface $em,
    ) {
        $this->waiterOrderProcessor = $waiterOrderProcessor;
        $this->em = $em;
    }

    /**
     * @param Client $client
     * @param LifecycleEventArgs $eventArgs
     * @throws \Exception
     */
    public function processingOrderByWaiter(Client $client, LifecycleEventArgs $eventArgs): void
    {
        $changeSet = $eventArgs->getObjectManager()->getUnitOfWork()->getEntityChangeSet($client);

        if (isset($changeSet['status'])) {
            $this->waiterOrderProcessor->processingOrder($client->getConnectedOrder());
        }
    }

    /**
     * @param Order $order
     */
    public function deliveryOrder(Order $order): void
    {
        if ($order->getStatus() !== Order::READY_TO_WAITER) {
            return;
        }

        $order->setStatus(Order::READY_TO_EAT);
        $kitchener = $order->getKitchener();
        $kitchener->removeOrder($order);
        $this->waiterOrderProcessor->bringFood($order);
        $this->em->flush();
    }
}