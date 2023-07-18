<?php

namespace App\Services\Cleaner;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientCleaner
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function removeAllClients(): void
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->delete(Client::class)
            ->getQuery()
            ->execute();
    }

}