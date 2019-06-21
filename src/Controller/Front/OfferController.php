<?php

namespace App\Controller\Front;

use App\Entity\Advert;
use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\AdvertStatusRepository;
use App\Repository\GamePlatformRepository;
use App\Repository\OfferRepository;
use App\Repository\OfferStatusRepository;
use App\Security\OfferVoter;
use App\Services\MailServices;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
	 * @param ObjectManager $em
	 * @param GamePlatformRepository $gamePlatformRepository
	 * @param OfferStatusRepository $offerStatusRepository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function new(Request $request, Advert $advert, ObjectManager $em, GamePlatformRepository $gamePlatformRepository, OfferStatusRepository $offerStatusRepository, MailServices $mailservices) {
		$offer = new Offer();
		$offer->setAdvert($advert);
		$form = $this->createForm(OfferType::class, $offer, [
			'action' => $this->generateUrl('offer_new', ['id' => $advert->getId()])
		]);
		$form->handleRequest($request);
		$errors = [];
        foreach ($form->getErrors(true) as $k => $error) {
            $errors[] = $error->getMessage();
		}
        if(!empty($errors)){
            return new JsonResponse($errors, 400);
        }
        if($form->isSubmitted() && $form->isValid()){
			$user = $this->getUser();
			$offer->setCreatedBy($user);
			$status = $offerStatusRepository->findOneBy(['name' => 'En cours de validation']);
			$offer->setOfferStatus($status);
			if($form->has('game')){
				$game = $form->get('game')->getData();
				$gamePlatform = $gamePlatformRepository->findOneByGameAndUser($game, $user);
				$offer->setGamePlatform($gamePlatform);
			}
            if ($mailservices->notifyOfferDemmand($advert->getCreatedBy()->getEmail(), $user)) {
                $this->addFlash('notice', 'Notification mail was sent successfully.');
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
		$this->denyAccessUnlessGranted(OfferVoter::PENDING, $offer);
		$form = $this->createForm(OfferType::class, $offer);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$user = $this->getUser();
			if($form->has('game')){
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

	/**
	 * @Route(path="/list/{id}", name="list", methods={"GET"})
	 * @param $id
	 * @param OfferRepository $offerRepository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listOffers($id, OfferRepository $offerRepository) {
		$offers = $offerRepository->findOffersByAdvert($id);
		return $this->render('Front/offer/list.html.twig', ['offers' => $offers]);
	}


	/**
	 * @Route(path="/accept/{id}", name="accept", methods={"POST"})
	 * @param Offer $offer
	 * @param EntityManagerInterface terface $em
	 * @param OfferRepository $offerRepository
	 * @param OfferStatusRepository $offerStatusRepository
	 *
	 * @param AdvertStatusRepository $advertStatusRepository
	 *
	 * @return JsonResponse
	 */
	public function acceptOffer(Offer $offer, EntityManagerInterface $em, OfferRepository $offerRepository,
								OfferStatusRepository $offerStatusRepository, AdvertStatusRepository $advertStatusRepository)
	{
		$accepted = $offerStatusRepository->findOneBy(['name' => 'Accepté']);
		$declined = $offerStatusRepository->findOneBy(['name' => 'Refusé']);
		$closed = $advertStatusRepository->findOneBy(['name' => 'Fermé']);
		$offer->setOfferStatus($accepted);
		$advert = $offer->getAdvert();
		$advert->setAdvertStatus($closed);
		/** @var $remainingOffers Offer[] **/
		$remainingOffers = $offerRepository->exludeByAdvert($offer->getId(), $offer->getAdvert()->getId());
		foreach ($remainingOffers as $remainingOffer) {
			$remainingOffer->setOfferStatus($declined);
		}
		$em->flush();
		$ids = array_map(function($offer) { return $offer->getId(); }, $remainingOffers);
		return new JsonResponse($ids);
	}

	/**
	 * @Route(path="/decline/{id}", name="decline", methods={"POST"})
	 */
	public function declineOffer(Offer $offer, EntityManagerInterface $em, OfferStatusRepository $offerStatusRepository) {
		$declined = $offerStatusRepository->findOneBy(['name' => 'Refusé']);
		$offer->setOfferStatus($declined);
		$em->flush();
		return new JsonResponse("declined {$offer->getId()}");
	}

}

