<?php

namespace App\Repository;

use App\Entity\AdvertStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdvertStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdvertStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdvertStatus[]    findAll()
 * @method AdvertStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdvertStatus::class);
    }

    // /**
    //  * @return AdvertStatus[] Returns an array of AdvertStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdvertStatus
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
