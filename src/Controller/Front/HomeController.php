<?php

namespace App\Controller\Front;

use Algolia\SearchBundle\IndexManagerInterface;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use App\Repository\PlatformRepository;
use Geocoder\Provider\AlgoliaPlaces\AlgoliaPlaces;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle6\Client;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, IndexManagerInterface $indexingManager, PlatformRepository $platformRepository,CategoryRepository $categoryRepository) : Response
    {
        if($this->getUser()){
            $user = $this->getUser();
            $latlong = "{$user->getLat()}, {$user->getLon()}";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, "https://places-dsn.algolia.net/1/places/reverse?aroundLatLng={$latlong}&hitsPerPage=1&language=fr");
            curl_setopt($curl, CURLOPT_HEADER, "X-Algolia-Application-Id: {$_ENV['ALGOLIA_APP_ID']}");
            curl_setopt($curl, CURLOPT_HEADER, "X-Algolia-API-Key: {$_ENV['ALGOLIA_API_KEY']}");
            $res = json_decode(curl_exec($curl), true);
            dump($res);
        }

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
