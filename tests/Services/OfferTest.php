<?php
//
//namespace App\Tests\Services;
//
//use App\Entity\Offer;
//use App\Repository\OfferRepository;
//use App\Repository\OfferStatusRepository;
//use App\Services\NotificationService;
//use App\Services\OfferService;
//use Doctrine\ORM\EntityManager;
//use PHPUnit\Framework\MockObject\MockObject;
//use PHPUnit\Framework\TestCase;
//
//class OfferTest extends TestCase
//{
//    /** @var $em MockObject  **/
//    private $em;
//
//    /** @var $offerRepository MockObject  **/
//    private $offerRepository;
//
//    /** @var $offerStatusRepository MockObject  **/
//    private $offerStatusRepository;
//
//    /** @var $offer Offer  **/
//    private $offer;
//
//    /** @var $notificationService MockObject  **/
//    private $notificationService;
//
//    /** @var $offerService OfferService  **/
//    private $offerService;
//
//    public function setUp()
//    {
//        $this->em = $this->createMock(EntityManager::class);
//        $this->offerRepository = $this->createMock(OfferRepository::class);
//        $this->offerStatusRepository = $this->createMock(OfferStatusRepository::class);
//        $this->offer = $this->createMock(Offer::class);
//        $this->notificationService = $this->createMock(NotificationService::class);
//        $this->offerService = new OfferService($this->em, $this->offerRepository, $this->offerStatusRepository, $this->notificationService);
//    }
//
//    public function testDeclineOffer()
//    {
//        $this->em->expects($this->once())->method('flush');
//        $this->notificationService->expects($this->once())->method('notifyAdvert')->willReturn(null);
//        $this->offerService->declineOffer($this->offer);
//        $this->assertEquals('Refusé', $this->offer->getOfferStatus()->getName());
//    }
//
//}