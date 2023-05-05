<?php

namespace App\Repository;

use App\Entity\Adslot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adslot>
 *
 * @method Adslot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adslot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adslot[]    findAll()
 * @method Adslot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdslotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adslot::class);
    }

    public function save(Adslot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Adslot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function queryWithJoin()
    {
        return $this->createQueryBuilder('s')
            ->addSelect('a')
            ->leftJoin('s.adverts', 'a');
    }

    public function findOneByName($value): ?Adslot
    {
        return $this->queryWithJoin()
            ->andWhere('s.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ; 
    }

    public function findOneByNameWithAds(string $value, array $context = null): ?Adslot
    {
        $query = $this->queryWithJoin()
            ->andWhere('s.name = :val')
            ->setParameter('val', $value)
        ; 

        if ($context) {
            $query->andWhere('a.context IN (:context)')
                ->setParameter('context', $context);
        }

        return $query->getQuery()->getOneOrNullResult();
    }
}
