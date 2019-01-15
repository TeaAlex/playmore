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
		$sql = <<<SQL
			SELECT i.id, i.name, g.release_date, e.name editor, c.name classification, c2.name category, d.name developper, plat.platforms, i.img_name file_name
			FROM game g
			JOIN item i ON g.id = i.id AND i.type = 'game'
			JOIN editor e ON g.editor_id = e.id
			JOIN classification c ON g.classification_id = c.id
			JOIN category c2 ON g.category_id = c2.id
			JOIN developper d ON g.developper_id = d.id
			JOIN
			(
			  SELECT GROUP_CONCAT(p.name SEPARATOR ' ') platforms, ip.item_id
			  FROM item_platform ip
			  JOIN platform p ON ip.platform_id = p.id
			  GROUP BY ip.item_id
			) plat ON plat.item_id = i.id
			ORDER BY i.id
SQL;
		return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();

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
