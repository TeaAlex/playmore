<?php

namespace App\Controller\Back;

use App\Entity\Developper;
use App\Form\DevelopperType;
use App\Repository\DevelopperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/developper")
 */
class DevelopperController extends AbstractController
{
    /**
     * @Route("/", name="developper_index", methods={"GET"})
     */
    public function index(DevelopperRepository $developperRepository): Response
    {
        return $this->render('Back/developper/list.html.twig', ['developpers' => $developperRepository->findAll()]);
    }

    /**
     * @Route("/new", name="developper_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $developper = new Developper();
        $form = $this->createForm(DevelopperType::class, $developper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($developper);
            $entityManager->flush();

            return $this->redirectToRoute('developper_index');
        }

        return $this->render('Back/developper/new.html.twig', [
            'developper' => $developper,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="developper_show", methods={"GET"})
     */
    public function show(Developper $developper): Response
    {
        return $this->render('Back/developper/show.html.twig', ['developper' => $developper]);
    }

    /**
     * @Route("/{slug}/edit", name="developper_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Developper $developper): Response
    {
        $form = $this->createForm(DevelopperType::class, $developper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('developper_index', ['slug' => $developper->getSlug()]);
        }

        return $this->render('Back/developper/edit.html.twig', [
            'developper' => $developper,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="developper_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Developper $developper): Response
    {
        if ($this->isCsrfTokenValid('delete'.$developper->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($developper);
            $entityManager->flush();
        }

        return $this->redirectToRoute('developper_index');
    }
}
