<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

	public function all(): array {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('id','id');
		$rsm->addScalarResult('name','name');
		$rsm->addScalarResult('release_date','release_date');
		$rsm->addScalarResult('editor','editor');
		$rsm->addScalarResult('classification','classification');
		$rsm->addScalarResult('category','category');
		$rsm->addScalarResult('developper','developper');
		$rsm->addScalarResult('platforms','platforms');
		$rsm->addScalarResult('file_name','file_name');
        $rsm->addScalarResult('slug','slug');
		$sql = <<<SQL
			SELECT g.id, g.name, g.release_date,g.slug, e.name editor, c.name classification, c2.name category, d.name developper, plat.platforms, g.img_name file_name
			FROM game g
			JOIN editor e ON g.editor_id = e.id
			JOIN classification c ON g.classification_id = c.id
			JOIN category c2 ON g.category_id = c2.id
			JOIN developper d ON g.developper_id = d.id
			JOIN
			(
			  SELECT GROUP_CONCAT(p.name SEPARATOR ' ') platforms, gp.game_id
			  FROM game_platform gp
			  JOIN platform p ON gp.platform_id = p.id
			  WHERE gp.deleted_at IS NULL
			  GROUP BY gp.game_id
			) plat ON plat.game_id = g.id
			ORDER BY g.id
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();

    }

    public function findGameNotInAdvertByUser($userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $sql = <<<SQL
            SELECT g.id
            FROM game g
            JOIN game_platform p on g.id = p.game_id
            JOIN game_platform_user gpu on p.id = gpu.game_platform_id AND gpu.user_id = :userId
            WHERE g.id NOT IN
            (
                SELECT g2.id
                FROM advert a
                JOIN game_platform gp on a.game_owned_id = gp.id
                JOIN game g2 on gp.game_id = g2.id
                WHERE a.created_by_id = :userId
            )
SQL;
        $ids = implode(',', array_column($this->getEntityManager()->createNativeQuery($sql, $rsm)->setParameters(["userId" => $userId])->getResult(), 'id'));

        $dql = <<<DQL
            SELECT g FROM App\Entity\Game g WHERE g.id IN ($ids)
DQL;
        return $this->_em->createQuery($dql)->getResult();

    }



    // /**
    //  * @return Game[] Returns an array of Game objects
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
    public function findOneBySomeField($value): ?Game
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
