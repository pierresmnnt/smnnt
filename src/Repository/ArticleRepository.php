<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Article::class);
        $this->paginator = $paginatorInterface;
    }

    /**
    * @return Article[] Returns an array of Article objects
    */
    public function findAllWithJoin(int $page, $limit = 8)
    {
        $query = $this->createQueryBuilder('a')
            ->addSelect('t')
            ->leftJoin('a.topics', 't')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
    * @return Article[] Returns an array of Article objects
    */
    public function findAllPublished(int $page, $limit = 8)
    {
        $query = $this->createQueryBuilder('a')
            ->addSelect('t')
            ->leftJoin('a.topics', 't')
            ->andWhere('a.published = TRUE')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }
    
    /**
     * @return Article[]
     */
    public function findLastPublished(int $limit = 3)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('t')
            ->leftJoin('a.topics', 't')
            ->andWhere('a.published = TRUE')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
