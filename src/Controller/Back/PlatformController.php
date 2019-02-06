<?php

namespace App\Controller\Back;

use App\Entity\Platform;
use App\Form\PlatformType;
use App\Repository\PlatformRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/platform")
 */
class PlatformController extends AbstractController
{
    /**
     * @Route("/", name="platform_index", methods={"GET"})
     */
    public function index(PlatformRepository $platformRepository): Response
    {
        return $this->render('Back/platform/index.html.twig', [
            'platforms' => $platformRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="platform_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $platform = new Platform();
        $form = $this->createForm(PlatformType::class, $platform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($platform);
            $entityManager->flush();

            return $this->redirectToRoute('platform_index');
        }

        return $this->render('Back/platform/new.html.twig', [
            'platform' => $platform,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="platform_show", methods={"GET"})
     */
    public function show(Platform $platform): Response
    {
        return $this->render('Back/platform/show.html.twig', [
            'platform' => $platform,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="platform_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Platform $platform): Response
    {
        $form = $this->createForm(PlatformType::class, $platform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('platform_index', [
                ['slug' => $platform->getSlug()],
            ]);
        }

        return $this->render('Back/platform/edit.html.twig', [
            'platform' => $platform,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="platform_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Platform $platform): Response
    {
        if ($this->isCsrfTokenValid('delete'.$platform->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($platform);
            $entityManager->flush();
        }

        return $this->redirectToRoute('platform_index');
    }
}
