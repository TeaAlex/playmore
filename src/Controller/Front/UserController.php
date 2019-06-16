<?php

namespace App\Controller\Front;


use App\Entity\Comment;
use App\Entity\Game;
use App\Entity\GamePlatform;
use App\Entity\Platform;
use App\Entity\User;
use App\Form\Front\UserGameType;
use App\Form\Front\UserType;
use App\Form\Front\CommentType;
use App\Repository\AdvertRepository;
use App\Repository\CommentRepository;
use App\Repository\GamePlatformRepository;
use App\Repository\OfferRepository;
use App\Repository\UserRepository;
use App\Security\CommentVoter;
use App\Security\UserVoter;
use App\Services\GeoCodingService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Class UserController
 * @package App\Controller\Front
 * @Route(path="/user", name="user_")
 */
class UserController extends AbstractController {

    /**
     * @Route(path="/geolocate", name="geolocate", methods={"GET"})
     */
    public function getUserLocation(ObjectManager $em, Request $request, GeoCodingService $geoCodingService)
    {
        $lat = $request->query->get('lat');
        $lon = $request->query->get('lon');
        /** @var $user User  **/
        $user = $this->getUser();
        $infos = $geoCodingService->reverseGeoCoding($lat, $lon);
        $geoCodingService->setUserGeo($infos, $user);
        $em->persist($user);
        $em->flush();
        return new JsonResponse($user->getAddress(), 200);
    }

	/**
	 * @Route(path="/{slug}", name="profile")
	 * @param User $user
	 * @param AdvertRepository $advertRepository
	 * @param UserRepository $userRepository
	 *
	 * @param CommentRepository $commentRepository
	 * @param OfferRepository $offerRepository
	 *
	 * @return Response
	 */
	public function profile(User $user, AdvertRepository $advertRepository, UserRepository $userRepository, CommentRepository $commentRepository, OfferRepository $offerRepository): Response {
		$adverts = $advertRepository->all($user->getId(), $user->getLat(), $user->getLon());
		$infos = $userRepository->findInfosByUser($user->getId());
		$offers = $offerRepository->findUserOffers($user->getId());
		$commentaires = $commentRepository->findBy(['createdTo' => $user->getId()]);
		$commentaire = new Comment();
		$form = $this->createForm(CommentType::class, $commentaire);

		return $this->render('Front/users/profile.html.twig', [
			'user' => $user,
			'adverts' => $adverts,
			'infos' => $infos,
			'offers' => $offers,
            'form' => $form->createView(),
            'commentaires' => $commentaires
		]);
	}


	/**
	 * @Route(path="/profile/{slug}/{page}", name="profile_2")
	 * @param User $user
	 * @param AdvertRepository $advertRepository
	 * @param UserRepository $userRepository
	 *
	 * @param CommentRepository $commentRepository
	 * @param OfferRepository $offerRepository
	 *
	 * @return Response
	 */
	public function profile2(User $user, $page = 'game', AdvertRepository $advertRepository, UserRepository $userRepository, CommentRepository $commentRepository, OfferRepository $offerRepository): Response {

	    switch ($page) {
            case 'game':
                $platforms = $userRepository->getGamesByUser($user->getId());
                break;
            case 'advert':
                $adverts = $advertRepository->all($user->getId(), false, $user->getLat(), $user->getLon());
                break;
            case 'offer':
                $offers = $offerRepository->getByUser($user->getId());
                break;
        }

		$infos = $userRepository->findInfosByUser($user->getId());
//		$offers = $offerRepository->findUserOffers($user->getId());
		$commentaires = $commentRepository->findBy(['createdTo' => $user->getId()]);
		$commentaire = new Comment();
		$form = $this->createForm(CommentType::class, $commentaire);

		return $this->render('Front/users/profile2.html.twig', [
			'user' => $user,
			'adverts' => $adverts ?? [],
			'infos' => $infos,
			'offers' => $offers ?? [],
            'form' => $form->createView(),
            'commentaires' => $commentaires,
            'platforms' => $platforms ?? [],
            'page' => $page
		]);
	}

	/**
	 * @Route(path="/edit/{slug}", name="edit")
	 * @param Request $request
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function edit(User $user, Request $request, ObjectManager $em, GeoCodingService $geo) {
		$this->denyAccessUnlessGranted(UserVoter::OWNER, $user);
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
		    $address = $geo->search($request->request->get("address"));
		    $geo->setUserGeo($address, $user);
			$em->flush();
			return $this->redirectToRoute('user_profile', ['slug' => $user->getSlug()]);
		}
		return $this->render('Front/users/edit.html.twig', [
			'form' => $form->createView(),
			'user' => $user
		]);
	}


    /**
     * @Route(path="/comment/newComment", name="comment", methods="GET|POST")
     * @param Request $request
     * @param ObjectManager $em
     * @return Response
     */
    public function newComment(Request $request, ObjectManager $em): Response
    {
        $user = $this->getUser();
        $destinataire = $this->getDoctrine()->getRepository(User::class)->find($request->get("destinataire"));
        $newRating = $request->request->get('_rating');
        $commentaire = new Comment();
        $form = $this->createForm(CommentType::class, $commentaire);
        $form->handleRequest($request);
        $commentaire->setCreatedBy($user);
        $commentaire->setRating($newRating);

        $rating = 0;
        $comments = $em->getRepository(Comment::class)->findBy(['createdTo' => $destinataire]);
        foreach ($comments as $comment) {
            $rating += $comment->getRating();
        }
        $finalRating = ($rating + $newRating) / (count($comments) + 1);
        $commentaire->setCreatedTo($destinataire);
        $destinataire->setRating($finalRating);

        if ($form->isSubmitted() && $form->isValid() && $commentaire->getCreatedBy() !== null &&  $commentaire->getCreatedTo() !== null) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('user_profile', ['slug' => $destinataire->getSlug()]);
        }

        return $this->render('Front/Comment/new.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route(path="/comment/{id}/edit", name="edit_comment", methods={"POST", "GET"})
     * @param Request $request
     * @param Comment $comment
     * @param ObjectManager $em
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editComment(Request $request, Comment $comment, ObjectManager $em): Response {
        $this->denyAccessUnlessGranted(CommentVoter::OWNER, $comment);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('user_profile', ['slug' => $comment->getCreatedTo()->getSlug()]);
        }
        return $this->render('Front/Comment/edit.html.twig', ['form' => $form->createView(), 'user' => $comment->getCreatedTo()]);
    }

	/**
	 * @Route(path="/game/add", name="game_add", methods={"POST", "GET"})
	 * @param Request $request
	 * @param ObjectManager $em
	 *
	 * @return Response
	 */
	public function addGame(Request $request, ObjectManager $em): Response {
		$user = $this->getUser();
		$gamePlatform = new GamePlatform();
		$form = $this->createForm(UserGameType::class, $gamePlatform, ["userId" => $user->getId()]);
		$req = $request->request;
		$p = $req->get('user_game')['platform'] ?? false;
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid() && $p){
			$platform = $em->getRepository(Platform::class)->find( $req->get('user_game')['platform']);
			$game = $em->getRepository(Game::class)->find( $req->get('user_game')['game']);
			$gamePlatform = $em->getRepository(GamePlatform::class)->findOneBy(['platform' => $platform, 'game' => $game]);
			$user->addGamePlatform($gamePlatform);
			$em->persist($user);
			$em->flush();
			return $this->redirectToRoute('user_profile', ['slug' => $user->getSlug()]);

        }
		return $this->render('Front/users/add_game.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route(path="/game/edit/{gamePlatform}", name="game_edit", methods={"POST", "GET"})
	 * @param Request $request
	 * @param GamePlatform $gamePlatform
	 *
	 * @param GamePlatformRepository $repository
	 *
	 * @param ObjectManager $em
	 *
	 * @return Response
	 */
	public function editGame(Request $request, GamePlatform $gamePlatform, GamePlatformRepository $repository, ObjectManager $em) {
		/** @var $user User  **/
		$user = $this->getUser();
		$form = $this->createForm(UserGameType::class, $gamePlatform );
		$form->handleRequest($request);
		if($request->isXmlHttpRequest()){
			return $this->render('Front/users/edit_game.html.twig', ['form' => $form->createView()]);
		}
		if($form->isSubmitted() && $form->isValid()){
			$game = $form->get('game')->getData();
			$platform = $form->get('platform')->getData();
			$gp = $repository->findOneBy(['game' => $game, 'platform' => $platform]);
			if($gp !== $gamePlatform){
				$user->addGamePlatform($gp);
				$user->removeGamePlatform($gamePlatform);
				$em->flush();
			}
			return $this->redirectToRoute('user_profile', ['slug' => $user->getSlug()]);
		}
		return $this->render('Front/users/edit_game.html.twig', ['form' => $form->createView()]);
	}








}