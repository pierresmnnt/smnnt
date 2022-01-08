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

    public function findLast(): ?Image
    {
        return $this->createQueryBuilder('i')
            ->addSelect('a')
            ->leftJoin('i.albums', 'a')
            ->orderBy('i.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * 
     */
    public function findSearch(SearchData $data, int $limit = 8)
    {
        $query = $this->createQueryBuilder('i')
            ->addSelect('a')
            ->leftJoin('i.albums', 'a')
            ->orderBy('i.id', 'DESC');

        if (!empty($data->getCategories())) {
            $query
                ->andWhere('a.id IN (:albums)')
                ->setParameter('albums', $data->getCategories());
        }

        $query = $query->getQuery();

        return $this->paginator->paginate($query, $data->getPage(), $limit);
    }
}
