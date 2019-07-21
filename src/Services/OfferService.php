<?php


namespace App\Services;

use App\Entity\Advert;
use App\Entity\Offer;
use App\Entity\User;
use App\Repository\AdvertStatusRepository;
use App\Repository\OfferRepository;
use App\Repository\OfferStatusRepository;

class OfferService
{
    /**
     * @var OfferRepository
     */
    private $offerRepository;
    /**
     * @var OfferStatusRepository
     */
    private $offerStatusRepository;
    /**
     * @var AdvertStatusRepository
     */
    private $advertStatusRepository;

    public function __construct(
        OfferRepository $offerRepository,
        OfferStatusRepository $offerStatusRepository,
        AdvertStatusRepository $advertStatusRepository
    )
    {
        $this->offerRepository = $offerRepository;
        $this->offerStatusRepository = $offerStatusRepository;
        $this->advertStatusRepository = $advertStatusRepository;
    }

    public function transaction(User &$from, User &$to, Offer $offer)
    {
        $price = $offer->getPrice();
        $from->setCoins($from->getCoins() - $price);
        $to->setCoins($to->getCoins() + $price);
    }

    public function acceptOffer(Offer &$offer)
    {
        $accepted = $this->offerStatusRepository->findOneBy(['name' => 'Accepté']);
        $offer->setOfferStatus($accepted);
    }

    public function closeAdvert(Advert &$advert)
    {
        $closed = $this->advertStatusRepository->findOneBy(["name" => "Fermé"]);
        $advert->setAdvertStatus($closed);
    }

    public function declineOffer(Offer &$offer)
    {
        $declined = $this->offerStatusRepository->findOneBy(['name' => 'Refusé']);
        $offer->setOfferStatus($declined);
    }

}