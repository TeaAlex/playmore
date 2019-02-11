<?php

namespace App\Controller\Front;

use App\Entity\Advert;
use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\GamePlatformRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OfferController
 * @package App\Controller
 * @Route(path="/offer", name="offer_")
 */
class OfferController extends AbstractController {

	/**
	 * @Route(path="/new/{id}", name="new", methods={"GET", "POST"})
	 * @param Request $request
	 * @param Advert $advert
	 *
	 * @param ObjectManager $em
	 *
	 * @param GamePlatformRepository $gamePlatformRepository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function new(Request $request, Advert $advert, ObjectManager $em, GamePlatformRepository $gamePlatformRepository) {
		$offer = new Offer();
		$offer->setAdvert($advert);
		$form = $this->createForm(OfferType::class, $offer);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$user = $this->getUser();
			$offer->setCreatedBy($user);
			if($form->get('game')){
				$game = $form->get('game');
				$gamePlatform = $gamePlatformRepository->findOneByGameAndUser($game, $user);
				$offer->setGamePlatform($gamePlatform);
			}
			$em->persist($offer);
			$em->flush();
			return $this->redirectToRoute('user_profile', ['slug' => $user->getSlug()]);
		}
		return $this->render('Front/offer/_form.html.twig', ['form' => $form->createView()]);
	}


}

