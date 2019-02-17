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
		$form = $this->createForm(OfferType::class, $offer, [
			'action' => $this->generateUrl('offer_new', ['id' => $advert->getId()])
		]);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$user = $this->getUser();
			$offer->setCreatedBy($user);
			if(in_array('game', $form->all())){
				$game = $form->get('game')->getData();
				$gamePlatform = $gamePlatformRepository->findOneByGameAndUser($game, $user);
				$offer->setGamePlatform($gamePlatform);
			}
			$em->persist($offer);
			$em->flush();
			return $this->redirectToRoute('user_profile', ['slug' => $user->getSlug()]);
		}
		return $this->render('Front/offer/_form.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @Route(path="/edit/{id}", name="edit", methods={"GET", "POST"} )
	 * @param Request $request
	 * @param Offer $offer
	 * @param GamePlatformRepository $gamePlatformRepository
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function edit(Request $request, Offer $offer, GamePlatformRepository $gamePlatformRepository, ObjectManager $em) {
		$form = $this->createForm(OfferType::class, $offer);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$user = $this->getUser();
			if($form->get('game') != null){
				$game = $form->get('game')->getData();
				$gamePlatform = $gamePlatformRepository->findOneByGameAndUser($game, $user);
				$offer->setGamePlatform($gamePlatform);
			}
			$em->persist($offer);
			$em->flush();
			return $this->redirectToRoute('user_profile', ['slug' => $user->getSlug()]);
		}
		return $this->render('Front/offer/edit.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @Route(path="/{id}", name="delete", methods={"DELETE"})
	 * @param Request $request
	 *
	 * @param Offer $offer
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function delete(Request $request, Offer $offer) {
//		$this->denyAccessUnlessGranted(AdvertVoter::OWNER, $advert);
		if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($offer);
			$entityManager->flush();
		}

		return $this->redirectToRoute('user_profile', ['slug' => $offer->getCreatedBy()->getSlug()]);
	}


}

