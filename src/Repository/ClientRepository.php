<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function save(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function dropClients(): void
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->delete(Client::class, 'c')
            ->getQuery()
            ->execute();
    }

    public function getAmountOfClientsWithTips(): int
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select('coalesce(count(o), 0)')
            ->innerJoin('c.connectedOrder', 'o')
            ->where('o.tips IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function removeAllClients(): void
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->delete(Client::class)
            ->getQuery()
            ->execute();
    }
}
