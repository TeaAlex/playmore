<?php

namespace App\Controller\Front;


use App\Entity\Advert;
use App\Entity\Game;
use App\Entity\User;
use App\Form\AdvertType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
		if($form->isSubmitted() && $form->isValid()){
			$em->persist($advert);
			$em->flush();
			return $this->redirectToRoute('advert_new');
		}
		return $this->render('Front/adverts/new.html.twig', [
			'form' => $form->createView()
		]);

	}

}






























