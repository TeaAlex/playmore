<?php

namespace App\Repository;

use App\Entity\AdvertKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdvertKind|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdvertKind|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdvertKind[]    findAll()
 * @method AdvertKind[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertKindRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdvertKind::class);
    }

    // /**
    //  * @return AdvertType[] Returns an array of AdvertType objects
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
    public function findOneBySomeField($value): ?AdvertType
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
