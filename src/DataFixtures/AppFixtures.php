<?php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Classification;
use App\Entity\Developper;
use App\Entity\Editor;
use App\Entity\Platform;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture {

	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder) {
		$this->encoder = $encoder;
	}

	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param ObjectManager $manager
	 */
	public function load( ObjectManager $manager ) {

//		$platforms = ['PS4', 'XBOX', 'SWITCH', 'PC'];
//		foreach ($platforms as $platform) {
//			$p = new Platform();
//			$p->setName($platform);
//			$manager->persist($p);
//		}
//
//		$developpers = ['Rockstar', 'Insomniac Games', 'Electronic Arts'];
//		foreach ( $developpers as $developper ) {
//			$d = new Developper();
//			$d->setName($developper);
//			$d->setUpdatedAt(new \DateTime());
//			$manager->persist($d);
//		}
//
//		$editors = ['Rockstar', 'Sony', 'Electronic Arts'];
//		foreach ( $editors as $editor ) {
//			$e = new Editor();
//			$e->setName($editor);
//			$e->setUpdatedAt(new \DateTime());
//			$manager->persist($e);
//		}
//
//		$classifications = ['3+', '7+', '12+', '16+', '18+'];
//		foreach ( $classifications as $classification ) {
//			$c = new Classification();
//			$c->setName($classification);
//			$manager->persist($c);
//		}
//
//		$categories = ['TPS', 'Open World', 'Sport'];
//		foreach ( $categories as $category ) {
//			$c = new Category();
//			$c->setName($category);
//			$manager->persist($c);
//		}

		$user = new User();
		$user->setUsername('playmore');
		$user->setEmail('playmore@playmore.com');
		$user->setPassword($this->encoder->encodePassword($user, 'toto'));
		$user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
		$user->setCoins(0);
		$user->setRating(0);
		$user->setUpdatedAt(new \DateTime('now'));
		$manager->persist($user);

		$manager->flush();

	}
}