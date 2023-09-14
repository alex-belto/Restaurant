<?php

namespace App\Repository;

use App\Entity\Kitchener;
use App\Entity\MenuItem;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        $qbWaiter = $this->createQueryBuilder('w');
        $qbKitchener = $this->createQueryBuilder('k');
        $qbMenuItem = $this->createQueryBuilder('mi');

        $qbWaiter
            ->update(Waiter::class, 'w')
            ->set('w.restaurant', 'NULL')
            ->set('w.tips', 0)
            ->getQuery()
            ->execute();

        $qbKitchener
            ->update(Kitchener::class, 'k')
            ->set('k.restaurant', 'NULL')
            ->set('k.tips', 0)
            ->getQuery()
            ->execute();

        $qbMenuItem
            ->update(MenuItem::class, 'mi')
            ->set('mi.restaurant', 'NULL')
            ->getQuery()
            ->execute();

        $qb
            ->delete(Restaurant::class)
            ->getQuery()
            ->execute();
    }
}
