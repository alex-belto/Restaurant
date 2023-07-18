<?php

namespace App\Repository;

use App\Entity\Kitchener;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kitchener>
 *
 * @method Kitchener|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kitchener|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kitchener[]    findAll()
 * @method Kitchener[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KitchenerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kitchener::class);
    }

    public function save(Kitchener $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Kitchener $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
