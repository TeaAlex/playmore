<?php

namespace App\Controller\Back;

use App\Entity\Game;
use App\Entity\GamePlatform;
use App\Form\GameType;
use App\Repository\GamePlatformRepository;
use App\Repository\GameRepository;
use App\Repository\PlatformRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game")
 */
class GameController extends AbstractController
{
	/**
	 * @Route("/", name="game_index", methods={"GET"})
	 * @param GameRepository $gameRepository
	 *
	 * @return Response
	 */
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('Back/game/index.html.twig', ['games' => $gameRepository->all()]);
    }

	/**
	 * @Route("/new", name="game_new", methods={"GET","POST"})
	 * @param Request $request
	 *
	 * @return Response
	 */
    public function new(Request $request): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
        	$platforms = $form->get('platforms')->getData();
	        foreach ( $platforms as $platform ) {
		        $gamePlatform = new GamePlatform();
		        $gamePlatform->setGame($game);
		        $gamePlatform->setPlatform($platform);
		        $entityManager->persist($gamePlatform);
        	}
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('game_index');
        }

        return $this->render('Back/game/new.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

	/**
	 * @Route("/{id}", name="game_show", methods={"GET"})
	 * @param Game $game
	 *
	 * @return Response
	 */
    public function show(Game $game): Response
    {
        return $this->render('Back/game/show.html.twig', ['game' => $game]);
    }

	/**
	 * @Route("/{id}/edit", name="game_edit", methods={"GET","POST"})
	 * @param Request $request
	 * @param EntityManagerInterface $em
	 * @param Game $game
	 * @param PlatformRepository $platformRepository
	 * @param GamePlatformRepository $gamePlatformRepository
	 *
	 * @return Response
	 */
    public function edit(Request $request, EntityManagerInterface $em, Game $game, PlatformRepository $platformRepository, GamePlatformRepository $gamePlatformRepository): Response
    {
    	$platforms = $platformRepository->findByGame($game);
        $form = $this->createForm(GameType::class, $game, ['platforms' => $platforms] );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
			$this->updatePlatforms($em, $game, $form, $platforms, $gamePlatformRepository);
			$em->flush();
            return $this->redirectToRoute('game_index', ['id' => $game->getId()]);
        }

        return $this->render('Back/game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

	/**
	 * @Route("/delete/{id}", name="game_delete", methods={"DELETE"})
	 * @param Request $request
	 * @param Game $game
	 *
	 * @return Response
	 */
    public function delete(Request $request, Game $game): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game_index');
    }

	private function updatePlatforms(EntityManagerInterface &$em, Game &$game, FormInterface &$form, array &$platforms, GamePlatformRepository &$gamePlatformRepository) {
		$updatedPlatforms = $form->get('platforms')->getData();
		$toRemove = array_udiff($platforms, $updatedPlatforms, function ($a, $b) {
			return $a->getId() - $b->getId();
		});
		$toAdd = array_udiff($updatedPlatforms, $platforms, function ($a, $b) {
			return $a->getId() - $b->getId();
		});

		foreach ($toRemove as $platform) {
			$gp = $gamePlatformRepository->findOneBy(['game' => $game, 'platform' => $platform]);
			$game->removeGamePlatform($gp);
			$em->remove($gp);
		}

		foreach ($toAdd as $platform) {
			$em->getFilters()->disable('softdeleteable');
			if($gp = $gamePlatformRepository->findOneBy(['game' => $game, 'platform' => $platform])){
				$gp->setDeletedAt(null);
			} else {
				$gp = new GamePlatform();
				$gp->setPlatform($platform);
				$gp->setGame($game);
			}
			$game->addGamePlatform($gp);
			$em->persist($gp);
		}
		$em->getFilters()->enable('softdeleteable');
	}

}
