<?php

namespace App\Tests\Services;

use App\Entity\Advert;
use App\Entity\AdvertStatus;
use App\Entity\Offer;
use App\Entity\OfferStatus;
use App\Entity\User;
use App\Repository\AdvertStatusRepository;
use App\Repository\OfferRepository;
use App\Repository\OfferStatusRepository;
use App\Services\NotificationService;
use App\Services\OfferService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;


class OfferTest extends TestCase
{
    /** @var $em MockObject  **/
    private $em;

    /** @var $offerRepository MockObject  **/
    private $offerRepository;

    /** @var $offerStatusRepository MockObject  **/
    private $offerStatusRepository;

    /** @var $offer MockObject  **/
    private $offer;

    /** @var $notificationService MockObject  **/
    private $notificationService;

    /** @var $offerService OfferService  **/
    private $offerService;

    /** @var $advertStatusRepository MockObject*/
    private $advertStatusRepository;

    public function setUp()
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->offerRepository = $this->createMock(OfferRepository::class);
        $this->offerStatusRepository = $this->createMock(OfferStatusRepository::class);
        $this->advertStatusRepository = $this->createMock(AdvertStatusRepository::class);
        $this->offer = $this->createMock(Offer::class);
        $this->notificationService = $this->createMock(NotificationService::class);
        $this->offerService = new OfferService(
                $this->offerRepository,
                $this->offerStatusRepository,
                $this->advertStatusRepository
        );
    }

    public function testTransaction()
    {
        $offer = new Offer();
        $offer->setPrice(5);
        $from = new User();
        $from->setCoins(20);
        $to = new User();
        $to->setCoins(10);

        $this->offerService->transaction($from, $to, $offer);
        $this->assertEquals(15, $from->getCoins());
        $this->assertEquals(15, $to->getCoins());
    }

    public function testAcceptOffer()
    {
        $offer = new Offer();
        $status = (new OfferStatus())->setName('Accepté');
        $this->offerStatusRepository->method('findOneBy')
            ->willReturn($status);
        $this->offerService->acceptOffer($offer);
        $this->assertEquals('Accepté', $offer->getOfferStatus()->getName());
    }

    public function testDeclineOffer()
    {
        $offer = new Offer();
        $status = (new OfferStatus())->setName('Refusé');
        $this->offerStatusRepository->method('findOneBy')
            ->willReturn($status);
        $this->offerService->acceptOffer($offer);
        $this->assertEquals('Refusé', $offer->getOfferStatus()->getName());
    }

    public function testCloseAdvert()
    {
        $advert = new Advert();
        $status = (new AdvertStatus())->setName('Fermé');
        $this->advertStatusRepository->method('findOneBy')
            ->willReturn($status);
        $this->offerService->closeAdvert($advert);
        $this->assertEquals('Fermé', $advert->getAdvertStatus()->getName());
    }

}