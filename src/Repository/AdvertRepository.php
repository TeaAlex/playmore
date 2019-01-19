<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Advert::class);
    }

	public function all(  ) {
    	$rsm = new ResultSetMapping();
    	$rsm->addScalarResult('advert_id','advert_id');
    	$rsm->addScalarResult('advert_kind_id','advert_kind_id');
    	$rsm->addScalarResult('start_date','start_date');
    	$rsm->addScalarResult('end_date','end_date');
    	$rsm->addScalarResult('price','price');
    	$rsm->addScalarResult('game_owned_id','game_owned_id');
    	$rsm->addScalarResult('game_owned_name','game_owned_name');
    	$rsm->addScalarResult('game_owned_img_name','game_owned_img_name');
    	$rsm->addScalarResult('game_wanted_id','game_wanted_id');
    	$rsm->addScalarResult('game_wanted_name','game_wanted_name');
    	$rsm->addScalarResult('game_wanted_img_name','game_wanted_img_name');
    	$rsm->addScalarResult('username','username');
		$sql = <<<SQL
			SELECT a.id advert_id, ak.id advert_kind_id, a.start_date, a.end_date, a.price, g.id game_owned_id,  g.name game_owned_name, g.img_name game_owned_img_name,
       		a.game_wanted_id game_wanted_id, i2.name game_wanted_name, i2.img_name game_wanted_img_name, u.username
			FROM advert a
			JOIN advert_kind ak ON a.advert_kind_id = ak.id
			JOIN game g ON a.game_owned_id = g.id
			JOIN user u ON a.created_by_id = u.id
			LEFT JOIN game i2 ON a.game_wanted_id = i2.id
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();

    }
    
    

    // /**
    //  * @return Advert[] Returns an array of Advert objects
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
    public function findOneBySomeField($value): ?Advert
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
