<?php

namespace App\Controller\Front;

use App\Repository\AdvertKindRepository;
use App\Repository\CategoryRepository;
use App\Repository\PlatformRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('Front/home/index.html.twig', [
            'platforms' => $platformRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'distances' => [5,10,15,20]
        ]);
    }


    /**
     * @Route("/filters", name="filters", methods={"GET"})
     * @param PlatformRepository $platformRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function renderFilters(PlatformRepository $platformRepository, CategoryRepository $categoryRepository, AdvertKindRepository $advertKindRepository)
    {
        return $this->render('Partials/_filters.html.twig', [
            'platforms' => $platformRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'distances' => [5,10,15,20],
            'advertKinds' => $advertKindRepository->findAll(),
            'filters' => [
                'game' => null,
                'userId' => null,
                'platform' => null,
                'advert_kind' => null,
                'distance' => null,
                'category' => null,
            ]
        ]);
    }


}
