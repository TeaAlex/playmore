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
		$rsm->addScalarResult('offer_id','offer_id');
		$rsm->addScalarResult('price','price');
		$rsm->addScalarResult('start_date','start_date');
		$rsm->addScalarResult('end_date','end_date');
		$rsm->addScalarResult('advert_id','advert_id');
		$rsm->addScalarResult('advert_name','advert_name');
		$rsm->addScalarResult('game_advert_name','game_advert_name');
		$rsm->addScalarResult('game_advert_img_name','game_advert_img_name');
		$rsm->addScalarResult('game_advert_platform','game_advert_platform');
		$rsm->addScalarResult('game_offer_name','game_offer_name');
		$rsm->addScalarResult('game_offer_img_name','game_offer_img_name');
		$rsm->addScalarResult('game_offer_platform','game_offer_platform');
		$rsm->addScalarResult('advert_user_slug','advert_user_slug');

		$sql = <<<SQL
			SELECT o.id offer_id, o.price, o.start_date, o.end_date,
			       a.id advert_id, ak.name advert_name,
			       g.name game_advert_name, g.img_name game_advert_img_name, p.name game_advert_platform,
			       g2.name game_offer_name, g2.img_name game_offer_img_name, p2.name game_offer_platform,
			       u.slug advert_user_slug
			FROM offer o
			JOIN advert a ON o.advert_id = a.id
			JOIN advert_kind ak ON a.advert_kind_id = ak.id
			JOIN game_platform gp ON a.game_owned_id = gp.id
			JOIN game g ON gp.game_id = g.id
			JOIN platform p ON gp.platform_id = p.id
			LEFT JOIN game_platform gp2 ON o.game_platform_id = gp2.id
			LEFT JOIN game g2 ON gp2.game_id = g2.id
			LEFT JOIN platform p2 ON gp2.platform_id = p2.id
			JOIN user u ON a.created_by_id = u.id
			WHERE o.created_by_id = :user
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(['user' => $user])->getResult();
	}

	public function findOffersByAdvert($advertId) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('offer_id','offer_id');
		$rsm->addScalarResult('price','price');
		$rsm->addScalarResult('start_date','start_date');
		$rsm->addScalarResult('end_date','end_date');
		$rsm->addScalarResult('advert_id','advert_id');
		$rsm->addScalarResult('advert_name','advert_name');
		$rsm->addScalarResult('game_advert_name','game_advert_name');
		$rsm->addScalarResult('game_advert_img_name','game_advert_img_name');
		$rsm->addScalarResult('game_advert_platform','game_advert_platform');
		$rsm->addScalarResult('game_offer_name','game_offer_name');
		$rsm->addScalarResult('game_offer_img_name','game_offer_img_name');
		$rsm->addScalarResult('game_offer_platform','game_offer_platform');
		$rsm->addScalarResult('advert_user_slug','advert_user_slug');

		$sql = <<<SQL
			SELECT o.id offer_id, o.price, o.start_date, o.end_date,
			       a.id advert_id, ak.name advert_name,
			       g.name game_advert_name, g.img_name game_advert_img_name, p.name game_advert_platform,
			       g2.name game_offer_name, g2.img_name game_offer_img_name, p2.name game_offer_platform,
			       u.slug advert_user_slug
			FROM offer o
			JOIN advert a ON o.advert_id = a.id
			JOIN advert_kind ak ON a.advert_kind_id = ak.id
			JOIN game_platform gp ON a.game_owned_id = gp.id
			JOIN game g ON gp.game_id = g.id
			JOIN platform p ON gp.platform_id = p.id
			LEFT JOIN game_platform gp2 ON o.game_platform_id = gp2.id
			LEFT JOIN game g2 ON gp2.game_id = g2.id
			LEFT JOIN platform p2 ON gp2.platform_id = p2.id
			JOIN user u ON a.created_by_id = u.id
			WHERE a.id = :advertId
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(['advertId' => $advertId])->getResult();
	}

}
