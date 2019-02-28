<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

	public function findContacts($userId) {
    	$rsm = new ResultSetMapping();
    	$rsm->addScalarResult('id','id');
    	$rsm->addScalarResult('username','username');
    	$rsm->addScalarResult('slug','slug');
    	$rsm->addScalarResult('img_name','img_name');
		$sql = <<<SQL
			SELECT u.id, u.username, u.slug, u.img_name
			FROM message m
			JOIN user u ON m.user_to_id = u.id
			WHERE m.user_from_id = :userId
			GROUP BY m.user_to_id
			UNION
			SELECT u.id, u.username, u.slug, u.img_name
			FROM message m
			JOIN user u ON m.user_from_id = u.id
			WHERE user_to_id = :userId
			GROUP BY m.user_from_id
SQL;
		return $this->getEntityManager()
		            ->createNativeQuery($sql, $rsm)
		            ->setParameters(['userId' => $userId])
		            ->getResult();

    }

	public function findMessages($userFrom, $userTo) {
    	$rsm = new ResultSetMapping();
		$rsm->addScalarResult('content','content');
		$rsm->addScalarResult('created_at','created_at');
		$rsm->addScalarResult('user_from_username','user_from_username');
		$rsm->addScalarResult('user_from_img','user_from_img');
		$rsm->addScalarResult('user_to_username','user_to_username');
		$rsm->addScalarResult('user_to_img','user_to_img');
    	$sql = <<<SQL
			SELECT m.content, m.created_at,
			       u.username user_from_username, u.img_name user_from_img,
			       u2.username user_to_username, u2.img_name user_to_img
			FROM message m 
			JOIN user u ON m.user_from_id = u.id
			JOIN user u2 ON m.user_to_id = u2.id
			WHERE (m.user_from_id = :userFrom AND m.user_to_id = :userTo) OR (m.user_from_id = :userTo AND m.user_to_id = :userFrom)
			ORDER BY m.created_at
SQL;
    	return $this->getEntityManager()
		    ->createNativeQuery($sql, $rsm)
		    ->setParameters(['userFrom' => $userFrom, 'userTo' => $userTo])
		    ->getResult();

    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
