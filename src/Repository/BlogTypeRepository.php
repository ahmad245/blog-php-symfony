<?php

namespace App\Repository;

use App\Entity\BlogType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BlogType|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogType|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogType[]    findAll()
 * @method BlogType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogType::class);
    }

    // /**
    //  * @return BlogType[] Returns an array of BlogType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogType
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
