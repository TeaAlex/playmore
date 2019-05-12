<?php

namespace App\Repository;

use App\Entity\GamePlatform;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GamePlatform|null find($id, $lockMode = null, $lockVersion = null)
 * @method GamePlatform|null findOneBy(array $criteria, array $orderBy = null)
 * @method GamePlatform[]    findAll()
 * @method GamePlatform[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamePlatformRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GamePlatform::class);
    }

	public function findOneByGameAndUser($game, $user) {
    	$dql = <<<DQL
			SELECT gp
			FROM App\Entity\GamePlatform gp
			JOIN gp.user u
			WHERE gp.game = :game AND u.id = :user 
DQL;

		return $this->_em->createQuery($dql)->setParameters(['game' => $game, 'user' => $user])->getOneOrNullResult();
    }



    // /**
    //  * @return GamePlatform[] Returns an array of GamePlatform objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GamePlatform
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
