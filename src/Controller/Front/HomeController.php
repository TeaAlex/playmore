<?php

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use App\Repository\PlatformRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route(name="app_security_")
*/
class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(PlatformRepository $platformRepository,CategoryRepository $categoryRepository) : \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('Front/home/index.html.twig', ['platforms' => $platformRepository->findAll(),
            'categories' => $categoryRepository->findAll()]);
    }

}
