<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

	public function findInfosByUser($userId) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('cnt_games','cnt_games');
		$rsm->addScalarResult('cnt_echange','cnt_echange');
		$rsm->addScalarResult('cnt_location','cnt_location');
		$sql = <<<SQL
			SELECT IFNULL(game.cnt_games, 0) cnt_games, IFNULL(echange.cnt_echange, 0) cnt_echange, IFNULL(location.cnt_location, 0) cnt_location
			FROM user u
			LEFT JOIN (SELECT COUNT(*) cnt_games, user_id FROM game_platform_user) game ON game.user_id = u.id
			LEFT JOIN (SELECT COUNT(*) cnt_echange, created_by_id FROM advert JOIN advert_kind ak ON advert.advert_kind_id = ak.id WHERE ak.name = 'Echange' ) echange ON echange.created_by_id = u.id
			LEFT JOIN (SELECT COUNT(*) cnt_location, created_by_id FROM advert JOIN advert_kind ak ON advert.advert_kind_id = ak.id WHERE ak.name = 'Location') location ON echange.created_by_id = u.id
			WHERE u.id = :id		
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(['id' => $userId])->getOneOrNullResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
