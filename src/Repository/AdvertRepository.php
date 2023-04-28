<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advertisement>
 *
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    public function save(Advert $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Advert $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function queryWithJoin()
    {
        return $this->createQueryBuilder('a')
            ->addSelect('s')
            ->leftJoin('a.adslots', 's');
    }

    /**
     * @return Advert[] Returns an array of Advert objects
     */
    public function findByAdslot(string $value, array $context = null)
    {
        $query = $this->queryWithJoin()
            ->andWhere('s.name IN (:val)')
            ->setParameter('val', $value)
            ->andWhere('a.active = TRUE')
        ; 

        if ($context) {
            $query->andWhere('a.context IN (:context)')
                ->setParameter('context', $context);
        }

        return $query->getQuery()->getResult();
    }
}
