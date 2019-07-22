<?php

namespace App\Tests\Repository;

use App\Entity\Advert;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCountSearchName()
    {
        $adverts = $this->entityManager
            ->getRepository(Advert::class)
            ->search(["game" => "Zelda"]);

        $this->assertCount(2, $adverts);
    }

    public function testCountEchange()
    {
        $adverts = $this->entityManager
            ->getRepository(Advert::class)
            ->search(["game" => "", "advert_kind" => [1]]);

        $this->assertCount(7, $adverts);
    }

    public function testCountLocation()
    {
        $adverts = $this->entityManager
            ->getRepository(Advert::class)
            ->search(["game" => "", "advert_kind" => [2]]);

        $this->assertCount(5, $adverts);
    }

    public function testCountPlatform()
    {
        $adverts = $this->entityManager
            ->getRepository(Advert::class)
            ->search(["game" => "", "platform" => [1]]);

        $this->assertCount(3, $adverts);
    }

    public function testDistanceUnder5Km()
    {
        $adverts = $this->entityManager
            ->getRepository(Advert::class)
            ->search(["game" => "", "distance" => "5", "userId" => 1, "lat" => 48.8566, "lon" => 2.3517]);

        $this->assertCount(3, $adverts);
    }

    public function testDistanceUnder10Km()
    {
        $adverts = $this->entityManager
            ->getRepository(Advert::class)
            ->search(["game" => "", "distance" => "10", "userId" => 1, "lat" => 48.8566, "lon" => 2.3517]);

        $this->assertCount(4, $adverts);
    }

    public function testDistanceUnder15Km()
    {
        $adverts = $this->entityManager
            ->getRepository(Advert::class)
            ->search(["game" => "", "distance" => "15", "userId" => 1, "lat" => 48.8566, "lon" => 2.3517]);

        $this->assertCount(4, $adverts);
    }

    public function testDistanceUnder20Km()
    {
        $adverts = $this->entityManager
            ->getRepository(Advert::class)
            ->search(["game" => "", "distance" => "20", "userId" => 1, "lat" => 48.8566, "lon" => 2.3517]);

        $this->assertCount(4, $adverts);
    }



    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
