<?php

namespace App\Controller\Back;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function edit(Request $request, Game $game): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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
}
