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

	public function all($userId, bool $all = false) {
    	$rsm = new ResultSetMapping();
    	$rsm->addScalarResult('advert_id','advert_id');
    	$rsm->addScalarResult('advert_kind_name','advert_kind_name');
    	$rsm->addScalarResult('start_date','start_date');
    	$rsm->addScalarResult('end_date','end_date');
    	$rsm->addScalarResult('price','price');
    	$rsm->addScalarResult('game_owned_id','game_owned_id');
    	$rsm->addScalarResult('game_owned_name','game_owned_name');
    	$rsm->addScalarResult('game_owned_img_name','game_owned_img_name');
    	$rsm->addScalarResult('game_owned_platform','game_owned_platform');
    	$rsm->addScalarResult('game_wanted_id','game_wanted_id');
    	$rsm->addScalarResult('game_wanted_name','game_wanted_name');
    	$rsm->addScalarResult('game_wanted_img_name','game_wanted_img_name');
    	$rsm->addScalarResult('game_wanted_platform','game_wanted_platform');
    	$rsm->addScalarResult('username','username');
    	$rsm->addScalarResult('user_id','user_id');
    	$rsm->addScalarResult('user_img_name','user_img_name');
    	$rsm->addScalarResult('user_slug','user_slug');
    	$rsm->addScalarResult('offer_cnt','offer_cnt');
    	$rsm->addScalarResult('advert_status','advert_status');

		$join = "";
    	if($all === true){
    		$where = "WHERE u.id != :userId AND astat.name = 'Ouvert'";
	    } else {
    		$where = "WHERE u.id = :userId";
	    }

		$sql = <<<SQL
			SELECT a.id advert_id, ak.name advert_kind_name , a.start_date, a.end_date, a.price, astat.name advert_status,
			       IFNULL(COUNT(o.id), 0) offer_cnt,
			       u.username, u.id user_id, u.img_name user_img_name, u.slug user_slug,
			       g.id game_owned_id, g.name game_owned_name, g.img_name game_owned_img_name, p.name game_owned_platform,
       			   g2.id game_wanted_id, g2.name game_wanted_name, g2.img_name game_wanted_img_name, p2.name game_wanted_platform
			FROM advert a
		  	JOIN advert_status astat ON a.advert_status_id = astat.id
		  	JOIN advert_kind ak ON a.advert_kind_id = ak.id
			JOIN game_platform gp ON a.game_owned_id = gp.id
			JOIN game g ON gp.game_id = g.id
			JOIN platform p ON gp.platform_id = p.id
			LEFT JOIN game_platform gp2 ON a.game_wanted_id = gp2.id
			LEFT JOIN game g2 ON gp2.game_id = g2.id
		  	LEFT JOIN platform p2 ON gp2.platform_id = p2.id
		  	LEFT JOIN offer o ON o.advert_id = a.id
			JOIN user u ON a.created_by_id = u.id
			$join
			$where
			GROUP BY a.id
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(["userId" => $userId])->getResult();
    }

}
