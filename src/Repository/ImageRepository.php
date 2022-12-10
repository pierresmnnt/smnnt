<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Image::class);
        $this->paginator = $paginator;
    }

    private function queryWithJoin()
    {
        return $this->createQueryBuilder('i')
            ->addSelect('a')
            ->leftJoin('i.albums', 'a')
            ->addSelect('c')
            ->leftJoin('i.gearCamera', 'c')
            ->addSelect('l')
            ->leftJoin('i.gearLens', 'l');
    }

    /**
     * @return Image[]
     */
    public function findAllWithJoin(int $page, bool $isInPortfolio = true, int $limit = 20)
    {
        $query = $this->queryWithJoin()
            ->andWhere('i.isInPortfolio = :val')
            ->setParameter('val', $isInPortfolio)
            ->orderBy('i.date', 'DESC')
            ->addOrderBy('i.id', 'DESC')
            ->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
     * @return Image
     */
    public function findImageById($value): ?Image
    {
        return $this->queryWithJoin()
            ->andWhere('i.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Image[]
     */
    public function findPortfolioSearch(SearchData $data, int $limit = 20)
    {
        $query = $this->queryWithJoin()
            ->andWhere('i.isInPortfolio = TRUE')
            ->orderBy('i.date', 'DESC')
            ->addOrderBy('i.id', 'DESC');

        if (!empty($data->getCategory())) {
            $query
                ->andWhere('a.id IN (:album)')
                ->setParameter('album', $data->getCategory());
        }

        $query = $query->getQuery();

        return $this->paginator->paginate($query, $data->getPage(), $limit);
    }

    /**
     * @return Image[]
     */
    public function findLast(int $limit = 3)
    {
        return $this->queryWithJoin()
            ->orderBy('i.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
