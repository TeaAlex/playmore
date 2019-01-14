<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @Vich\Uploadable()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"game" = "Game", "accesory" = "Accessory"})
 */
abstract class Item
{
    #region Props

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;


	/**
	 * @var File|null
	 * @Vich\UploadableField(mapping="item_image", fileNameProperty="imgName")
	 */
    protected $imgFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    protected $imgName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Platform", inversedBy="items")
     */
    protected $platform;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advert", mappedBy="itemOwned")
     */
    private $adverts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="item")
     */
    private $offers;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    # endregion

    public function __construct()
    {
        $this->platform = new ArrayCollection();
        $this->adverts = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    #region Getters & Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return Collection|Platform[]
     */
    public function getPlatform(): Collection
    {
        return $this->platform;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platform->contains($platform)) {
            $this->platform[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platform->contains($platform)) {
            $this->platform->removeElement($platform);
        }

        return $this;
    }

    /**
     * @return Collection|Advert[]
     */
    public function getAdverts(): Collection
    {
        return $this->adverts;
    }

    public function addAdvert(Advert $advert): self
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts[] = $advert;
            $advert->setItemOwned($this);
        }

        return $this;
    }

    public function removeAdvert(Advert $advert): self
    {
        if ($this->adverts->contains($advert)) {
            $this->adverts->removeElement($advert);
            // set the owning side to null (unless already changed)
            if ($advert->getItemOwned() === $this) {
                $advert->setItemOwned(null);
            }
        }

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
            $offer->setItem($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getItem() === $this) {
                $offer->setItem(null);
            }
        }

        return $this;
    }

	/**
	 * @return File|null
	 */
	public function getImgFile(): ?File {
        return $this->imgFile;
    }

	/**
	 * @param File|null $imgFile
	 *
	 * @return Item
	 * @throws \Exception
	 */
	public function setImgFile( ?File $imgFile ): Item {
        $this->imgFile = $imgFile;
		if($this->imgFile instanceof UploadedFile){
			$this->updatedAt = new \DateTime('now');
		}
        return $this;
	}

	/**
	 * @return string|null
	 */
	public function getImgName(): ?string {
         		return $this->imgName;
         	}

	/**
	 * @param string|null $imgName
	 *
	 * @return Item
	 */
	public function setImgName( ?string $imgName ): Item {
	    $this->imgName = $imgName;
	    return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }



    #endregion
}
