<?php

namespace App\Repository;

use App\Entity\Developper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Developper|null find($id, $lockMode = null, $lockVersion = null)
 * @method Developper|null findOneBy(array $criteria, array $orderBy = null)
 * @method Developper[]    findAll()
 * @method Developper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevelopperRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Developper::class);
    }

    // /**
    //  * @return Developper[] Returns an array of Developper objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Developper
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
