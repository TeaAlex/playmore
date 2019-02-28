<?php

namespace App\Controller\Front;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use App\Repository\PlatformRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;

/**
* @Route(name="app_security_")
*/
class HomeController extends AbstractController
{
	/**
		 * @Route("/home", name="home")
	 * @param PlatformRepository $platformRepository
	 * @param CategoryRepository $categoryRepository
	 *
	 * @return Response
	 */
    public function index(PlatformRepository $platformRepository,CategoryRepository $categoryRepository) : Response
    {
        return $this->render('Front/home/index.html.twig', ['platforms' => $platformRepository->findAll(),
            'categories' => $categoryRepository->findAll()]);
    }
    /**
     * @Route("/results", name="results")
     */
    public function results(Request $request, AdvertRepository $advertrepository) : Response
    {
        $params = [];

        $params['game'] = $request->get('game', null);
        $params['platform'] = $request->get('platform', null);
        $params['category'] = $request->get('category', null);
        $params['userId'] = $this->getUser() ? $this->getUser()->getId() : null;

        $annonces = $advertrepository->search($params);

        return $this->render('Front/home/results.html.twig', [
        	'adverts' => $annonces,
	        'query' => $request->get('game', null)
        ]);
    }

}
