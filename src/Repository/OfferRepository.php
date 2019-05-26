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
		$rsm->addScalarResult('offer_created_by','offer_created_by');
		$rsm->addScalarResult('offer_created_by_slug','offer_created_by_slug');
		$rsm->addScalarResult('offer_created_by_username','offer_created_by_username');
		$rsm->addScalarResult('price','price');
		$rsm->addScalarResult('start_date','start_date');
		$rsm->addScalarResult('end_date','end_date');
		$rsm->addScalarResult('offer_status','offer_status');
		$rsm->addScalarResult('advert_id','advert_id');
		$rsm->addScalarResult('advert_name','advert_name');
		$rsm->addScalarResult('advert_created_by','advert_created_by');
		$rsm->addScalarResult('game_advert_name','game_advert_name');
		$rsm->addScalarResult('game_advert_img_name','game_advert_img_name');
		$rsm->addScalarResult('game_advert_platform','game_advert_platform');
		$rsm->addScalarResult('game_offer_name','game_offer_name');
		$rsm->addScalarResult('game_offer_img_name','game_offer_img_name');
		$rsm->addScalarResult('game_offer_platform','game_offer_platform');
		$rsm->addScalarResult('advert_user_slug','advert_user_slug');

		$sql = <<<SQL
			SELECT o.id offer_id, o.created_by_id offer_created_by, o.price, o.start_date, o.end_date,
			       u2.slug offer_created_by_slug, u2.username offer_created_by_username,
			       os.name offer_status,
			       a.id advert_id, ak.name advert_name, a.created_by_id advert_created_by,
			       g.name game_advert_name, g.img_name game_advert_img_name, p.name game_advert_platform,
			       g2.name game_offer_name, g2.img_name game_offer_img_name, p2.name game_offer_platform,
			       u.slug advert_user_slug
			FROM offer o
			JOIN user u2 ON o.created_by_id = u2.id
		  	JOIN offer_status os ON o.offer_status_id = os.id
			JOIN advert a ON o.advert_id = a.id
			JOIN advert_kind ak ON a.advert_kind_id = ak.id
			JOIN game_platform gp ON a.game_owned_id = gp.id
			JOIN game g ON gp.game_id = g.id
			JOIN platform p ON gp.platform_id = p.id
			LEFT JOIN game_platform gp2 ON o.game_platform_id = gp2.id
			LEFT JOIN game g2 ON gp2.game_id = g2.id
			LEFT JOIN platform p2 ON gp2.platform_id = p2.id
			JOIN user u ON a.created_by_id = u.id
			WHERE o.created_by_id = :user AND o.deleted_at IS NULL
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(['user' => $user])->getResult();
	}

	public function findOffersByAdvert($advertId) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('offer_id','offer_id');
		$rsm->addScalarResult('offer_created_by','offer_created_by');
		$rsm->addScalarResult('offer_created_by_slug','offer_created_by_slug');
		$rsm->addScalarResult('offer_created_by_username','offer_created_by_username');
		$rsm->addScalarResult('price','price');
		$rsm->addScalarResult('start_date','start_date');
		$rsm->addScalarResult('end_date','end_date');
		$rsm->addScalarResult('offer_status','offer_status');
		$rsm->addScalarResult('advert_id','advert_id');
		$rsm->addScalarResult('advert_name','advert_name');
		$rsm->addScalarResult('advert_created_by','advert_created_by');
		$rsm->addScalarResult('game_advert_name','game_advert_name');
		$rsm->addScalarResult('game_advert_img_name','game_advert_img_name');
		$rsm->addScalarResult('game_advert_platform','game_advert_platform');
		$rsm->addScalarResult('game_offer_name','game_offer_name');
		$rsm->addScalarResult('game_offer_img_name','game_offer_img_name');
		$rsm->addScalarResult('game_offer_platform','game_offer_platform');
		$rsm->addScalarResult('advert_user_slug','advert_user_slug');

		$sql = <<<SQL
			SELECT o.id offer_id, o.created_by_id offer_created_by, o.price, o.start_date, o.end_date,
			       u2.slug offer_created_by_slug, u2.username offer_created_by_username,
			       os.name offer_status,
			       a.id advert_id, ak.name advert_name, a.created_by_id advert_created_by,
			       g.name game_advert_name, g.img_name game_advert_img_name, p.name game_advert_platform,
			       g2.name game_offer_name, g2.img_name game_offer_img_name, p2.name game_offer_platform,
			       u.slug advert_user_slug
			FROM offer o
			JOIN user u2 ON o.created_by_id = u2.id
		  	JOIN offer_status os ON o.offer_status_id = os.id
			JOIN advert a ON o.advert_id = a.id
			JOIN advert_kind ak ON a.advert_kind_id = ak.id
			JOIN game_platform gp ON a.game_owned_id = gp.id
			JOIN game g ON gp.game_id = g.id
			JOIN platform p ON gp.platform_id = p.id
			LEFT JOIN game_platform gp2 ON o.game_platform_id = gp2.id
			LEFT JOIN game g2 ON gp2.game_id = g2.id
			LEFT JOIN platform p2 ON gp2.platform_id = p2.id
			JOIN user u ON a.created_by_id = u.id
			WHERE a.id = :advertId AND o.deleted_at IS NULL
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(['advertId' => $advertId])->getResult();
	}

	public function exludeByAdvert($offerId, $advertId) {
		$dql = <<<DQL
			SELECT o 
			FROM App\Entity\Offer o 
			WHERE o.id NOT IN (:offerId)
			AND o.advert = :advertId
DQL;
		return $this->_em->createQuery($dql)->setParameters([
			'offerId' => $offerId,
			'advertId' => $advertId
		])->getResult();

	}

    public function getByUser($userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('offer_id', 'offer_id');
        $rsm->addScalarResult('advert_id', 'advert_id');
        $rsm->addScalarResult('price', 'price');
        $rsm->addScalarResult('start_date', 'start_date');
        $rsm->addScalarResult('end_date', 'end_date');
        $rsm->addScalarResult('game_name', 'game_name');
        $rsm->addScalarResult('game_img_name', 'game_img_name');
        $rsm->addScalarResult('platform', 'platform');
        $rsm->addScalarResult('username', 'username');
        $rsm->addScalarResult('advert_kind', 'advert_kind');

        $sql = <<<SQL
            SELECT o.id offer_id, o.advert_id, o.price, o.start_date, o.end_date,
                   g.name game_name, g.img_name game_img_name, 
                   p.name platform,
                   u.username, 
                   ak.name advert_kind
            FROM offer o
            JOIN advert a on o.advert_id = a.id
            JOIN advert_kind ak on a.advert_kind_id = ak.id
            JOIN user u on a.created_by_id = u.id
            LEFT JOIN game_platform gp on o.game_platform_id = gp.id
            LEFT JOIN game g on gp.game_id = g.id
            LEFT JOIN platform p on gp.platform_id = p.id
            WHERE o.created_by_id = :userId
SQL;
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(['userId' => $userId])->getResult();

    }

}
