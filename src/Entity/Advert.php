<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 */
class Advert
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GamePlatform")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gameOwned;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GamePlatform")
     * @ORM\JoinColumn(name="game_wanted_id", referencedColumnName="id", nullable=true)
     */
    private $gameWanted;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

	/**
	 * @var $createdAt \DateTime
	 * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
	 */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AdvertStatus")
     */
    private $advertStatus;

    /**
     * @ORM\ManyToOne(targetEntity="AdvertKind")
     */
    private $advertKind;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="adverts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="advert")
     */
    private $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameOwned(): ?GamePlatform
    {
        return $this->gameOwned;
    }

    public function setGameOwned(?GamePlatform $gameOwned): self
    {
        $this->gameOwned = $gameOwned;

        return $this;
    }

    public function getGameWanted(): ?GamePlatform
    {
        return $this->gameWanted;
    }

    public function setGameWanted(?GamePlatform $gameWanted): self
    {
        $this->gameWanted = $gameWanted;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getAdvertStatus(): ?AdvertStatus
    {
        return $this->advertStatus;
    }

    public function setAdvertStatus(?AdvertStatus $advertStatus): self
    {
        $this->advertStatus = $advertStatus;

        return $this;
    }

    public function getAdvertKind(): ?AdvertKind
    {
        return $this->advertKind;
    }

    public function setAdvertKind(?AdvertKind $advertKind): self
    {
        $this->advertKind = $advertKind;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setAdvert($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getAdvert() === $this) {
                $offer->setAdvert(null);
            }
        }

        return $this;
    }

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTime {
		return $this->createdAt;
	}

	/**
	 * @param \DateTime $createdAt
	 *
	 * @return Advert
	 */
	public function setCreatedAt(\DateTime $createdAt): Advert {
		$this->createdAt = $createdAt;

		return $this;
	}


}
