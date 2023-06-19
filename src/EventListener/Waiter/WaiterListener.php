<?php

namespace App\EventListener\Waiter;

use Doctrine\Persistence\Event\LifecycleEventArgs;

class WaiterListener
{
    public function postUpdate(LifecycleEventArgs $eventArgs) {
        $entity = $eventArgs->getObject();
        //I get tips
    }

}