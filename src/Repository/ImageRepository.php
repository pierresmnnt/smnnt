<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * @return Image[]
     */
    public function findSearch(SearchData $data)
    {
        $query = $this->createQueryBuilder('i')
            ->leftJoin('i.categories', 'c')
            ->addSelect('c')
        ;

        if (!empty($data->getCategories())) {
            $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $data->getCategories());
        }

        return $query
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
