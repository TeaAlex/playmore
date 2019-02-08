<?php

namespace App\Controller\Front;


use App\Entity\Advert;
use App\Entity\GamePlatform;
use App\Form\Front\AdvertType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdvertController
 * @package App\Controller\Front
 * @Route(path="/advert", name="advert_")
 */
class AdvertController extends AbstractController
{
    /**
     * @Route(path="/", name="list")
     * @param Request $request
     * @return Response
     */
    public function showAll(Request $request): Response
    {
    	$em = $this->getDoctrine()->getManager();
    	$adverts = $em->getRepository(Advert::class)->all();
        return $this->render('Front/adverts/show_all.html.twig', ['adverts' => $adverts]);
    }

    
	/**
	 * @Route(path="/new", name="new", methods={"POST", "GET"})
	 * @param Request $request
	 * @param ObjectManager $em
	 *
	 * @return Response
	 */
	public function new(Request $request, ObjectManager $em) {
        $advert = new Advert();
        $user = $this->getUser();
        $advert->setCreatedBy($user);
        $form = $this->createForm(AdvertType::class, $advert);
		$form->handleRequest($request);
		if($request->isXmlHttpRequest()){
			return $this->render('Front/adverts/new.html.twig', ['form' => $form->createView()]);
		}
		if($form->isSubmitted() && $form->isValid()){
			$this->saveGamePlatform($em, $form, $advert);
			$em->persist($advert);
			$em->flush();
			return $this->redirectToRoute('user_profile', ['slug' => $user->getSlug()]);
		}
		return $this->render('Front/adverts/new.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route(path="/edit/{id}", name="edit", methods={"POST", "GET"})
	 * @param Request $request
	 * @param Advert $advert
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function edit(Request $request, Advert $advert, ObjectManager $em): Response {
		$form = $this->createForm(AdvertType::class, $advert);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$em->flush();
			return $this->redirectToRoute('user_profile', ['id' => $advert->getCreatedBy()->getId()]);
		}
		return $this->render('Front/adverts/edit.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @Route(path="/{id}", name="delete", methods={"DELETE"})
	 * @param Request $request
	 * @param Advert $advert
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function delete(Request $request, Advert $advert) {
		if ($this->isCsrfTokenValid('delete'.$advert->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($advert);
			$entityManager->flush();
		}

		return $this->redirectToRoute('user_profile', ['slug' => $advert->getCreatedBy()->getSlug()]);
	}




	private function saveGamePlatform(ObjectManager &$em, FormInterface &$form, Advert &$advert) {
		$repo = $em->getRepository( GamePlatform::class );
		$gameOwned = $repo->findOneByGameAndUser($form->get('gameOwned')->getData(), $this->getUser());
		$advert->setGameOwned($gameOwned);
		if($form->get('advertKind')->getData()->getName() == 'Echange' && $form->get('gameWanted') && $form->get('platform')){
			$gameWanted = $repo->findOneBy(['game' => $form->get('gameWanted')->getData(), 'platform' => $form->get('platform')->getData()]);
			$advert->setGameWanted($gameWanted);
		}
	}

}






























