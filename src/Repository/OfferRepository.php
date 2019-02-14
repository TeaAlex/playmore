<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bridge\Doctrine\RegistryInterface;


class OfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Offer::class);
    }

	public function findUserOffers($user) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('id','id');
		$rsm->addScalarResult('advert_id','advert_id');
		$rsm->addScalarResult('advert_author','advert_author');
		$rsm->addScalarResult('advert_game','advert_game');
		$rsm->addScalarResult('start_date','start_date');
		$rsm->addScalarResult('end_date','end_date');
		$rsm->addScalarResult('game','game');
		$rsm->addScalarResult('game_img','game_img');
		$rsm->addScalarResult('platform','platform');
		$rsm->addScalarResult('price','price');
		$sql = <<<SQL
			SELECT o.id, o.advert_id, u.slug advert_author, o.start_date, o.end_date, g.name game, g.img_name game_img, p.name platform, o.price
			FROM offer o
			JOIN advert a ON o.advert_id = a.id
			JOIN user u ON a.created_by_id = u.id
			LEFT JOIN game_platform_user gpu ON o.game_platform_id
			LEFT JOIN game_platform gp ON gpu.game_platform_id = gp.id
			LEFT JOIN game g ON gp.game_id = g.id
			LEFT JOIN platform p ON gp.platform_id = p.id
			WHERE o.created_by_id = :user
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(['user' => $user])->getResult();
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
