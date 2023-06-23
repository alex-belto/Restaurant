<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 *
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function save(Restaurant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Restaurant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function dropRestaurant(): void
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->delete('restaurant', 'r')
            ->getQuery()
            ->execute();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getAmountOfClientsWithTips(Restaurant $restaurant): int
    {
        $qb = $this->createQueryBuilder('r');

        return $qb
            ->select('coalesce(count(o), 0)')
            ->innerJoin(Waiter::class, 'w', Join::WITH, 'r.id = w.restaurant')
            ->innerJoin(Order::class, 'o', Join::WITH, 'o.waiter = w.id')
            ->where('o.tips != null')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Restaurant[] Returns an array of Restaurant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Restaurant
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
