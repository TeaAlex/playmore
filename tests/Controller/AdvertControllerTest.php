<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdvertControllerTest extends WebTestCase {

    private function login($isAdmin = true)
    {
        if(!$isAdmin){
            $username = 'toto@toto.com';
        }
        $username = 'playmore@playmore.com';
//        $client = static::createPantherClient()
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerNode->form([
            '_username' => $username,
            '_password' => 'toto'
        ]);
        $client->submit($form);
        $client->followRedirect();
        return $client;
    }

    public function testHomePageStatusCode()
    {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testWantedGameLabel()
    {
        $client = $this->login();
        $crawler = $client->clickLink('Ajouter une annonce');
        $button = $crawler->selectButton('Enregistrer');
        $form = $button->form();
        $form['advert[advertKind]']->select(1);
        $this->assertGreaterThan(
            0,
                $crawler->filter('html:contains("Jeu recherché")')->count()
        );
    }

    public function testPriceLabel()
    {
        $client = $this->login();
        $crawler = $client->clickLink('Ajouter une annonce');
        $button = $crawler->selectButton('Enregistrer');
        $form = $button->form();
        $form['advert[advertKind]']->select(2);
        $this->assertGreaterThan(
            0,
                $crawler->filter('html:contains("Prix")')->count()
        );
    }

    public function testAccessAdmin()
    {
        $client = $this->login();
        $client->clickLink('Admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeniedAccessAdmin()
    {
        $client = $this->login(false);
        $client->request('GET', '/admin/game');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }


}