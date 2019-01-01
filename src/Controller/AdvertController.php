<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends AbstractController
{
    /**
     * @Route(path="/adverts", name="show_adverts")
     * @param Request $request
     * @return Response
     */
    public function showAll(Request $request): Response
    {
        return $this->render('Front/show_all.html.twig', []);
    }

}






























