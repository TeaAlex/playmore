<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable()
 * @UniqueEntity("email")


 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;
    /**
     * @ORM\Column(type="integer", length=255, options={"default":0})
     */
    private $rating;
    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $coins;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
    private $city;

	/**
	 * @var int|null
	 * @ORM\Column(type="integer", nullable=true)
	 */
    private $postalCode;

	/**
	 * @var File|null
	 * @Vich\UploadableField(mapping="user_image", fileNameProperty="imgName")
	 */
	private $imgFile;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $imgName;

	/**
	 * @var $updatedAt \DateTime
	 * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
	 */
	private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advert", mappedBy="createdBy")
     */
    private $adverts;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="createdBy")
     */
    private $offers;
    /**
     * @ORM\Column(name="roles", type="json"), options={"default":0}
     */
    private $roles = [];
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="createdBy")
     */
    private $commentsCreated;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="createdTo")
     */
    private $commentsReceived;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GamePlatform", mappedBy="user")
     */
    private $gamePlatforms;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Platform")
     */
    private $platforms;

    /**
     * @var string
     * @Gedmo\Slug(fields={"username"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $slug;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    /**
     * @var $lat float
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;

    /**
     * @var $lon float
     * @ORM\Column(type="float", nullable=true)
     */
    private $lon;

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function __construct()
    {
        $this->adverts          = new ArrayCollection();
        $this->offers           = new ArrayCollection();
        $this->commentsCreated  = new ArrayCollection();
        $this->commentsReceived = new ArrayCollection();
        $this->rating           = 0;
        $this->coins            = 0;
        $this->gamePlatforms = new ArrayCollection();
        $this->platforms = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }
    public function getRating(): ?int
    {
        return $this->rating;
    }
    public function setRating(int $rating): self
    {
        $this->rating = $rating;
        return $this;
    }
    public function getCoins(): ?int
    {
        return $this->coins;
    }
    public function setCoins(int $coins): self
    {
        $this->coins = $coins;
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
            $advert->setCreatedBy($this);
        }
        return $this;
    }
    public function removeAdvert(Advert $advert): self
    {
        if ($this->adverts->contains($advert)) {
            $this->adverts->removeElement($advert);
            // set the owning side to null (unless already changed)
            if ($advert->getCreatedBy() === $this) {
                $advert->setCreatedBy(null);
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
            $offer->setCreatedBy($this);
        }
        return $this;
    }
    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getCreatedBy() === $this) {
                $offer->setCreatedBy(null);
            }
        }
        return $this;
    }
    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    /**
     * @return Collection|Comment[]
     */
    public function getCommentsCreated(): Collection
    {
        return $this->commentsCreated;
    }
    public function addCommentsCreated(Comment $commentsCreated): self
    {
        if (!$this->commentsCreated->contains($commentsCreated)) {
            $this->commentsCreated[] = $commentsCreated;
            $commentsCreated->setCreatedBy($this);
        }
        return $this;
    }
    public function removeCommentsCreated(Comment $commentsCreated): self
    {
        if ($this->commentsCreated->contains($commentsCreated)) {
            $this->commentsCreated->removeElement($commentsCreated);
            // set the owning side to null (unless already changed)
            if ($commentsCreated->getCreatedBy() === $this) {
                $commentsCreated->setCreatedBy(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|Comment[]
     */
    public function getCommentsReceived(): Collection
    {
        return $this->commentsReceived;
    }
    public function addCommentsReceived(Comment $commentsReceived): self
    {
        if (!$this->commentsReceived->contains($commentsReceived)) {
            $this->commentsReceived[] = $commentsReceived;
            $commentsReceived->setCreatedTo($this);
        }
        return $this;
    }
    public function removeCommentsReceived(Comment $commentsReceived): self
    {
        if ($this->commentsReceived->contains($commentsReceived)) {
            $this->commentsReceived->removeElement($commentsReceived);
            // set the owning side to null (unless already changed)
            if ($commentsReceived->getCreatedTo() === $this) {
                $commentsReceived->setCreatedTo(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|GamePlatform[]
     */
    public function getGamePlatforms(): Collection
    {
        return $this->gamePlatforms;
    }

    public function addGamePlatform(GamePlatform $gamePlatform): self
    {
        if (!$this->gamePlatforms->contains($gamePlatform)) {
            $this->gamePlatforms[] = $gamePlatform;
            $gamePlatform->addUser($this);
        }

        return $this;
    }

    public function removeGamePlatform(GamePlatform $gamePlatform): self
    {
        if ($this->gamePlatforms->contains($gamePlatform)) {
            $this->gamePlatforms->removeElement($gamePlatform);
            $gamePlatform->removeUser($this);
        }

        return $this;
    }

	public function getGames(): array {
		return array_map(function(GamePlatform $gamePlatform) {
			return $gamePlatform->getGame();
		}, $this->gamePlatforms->toArray());
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt(): \DateTime {
		return $this->updatedAt;
	}

	/**
	 * @param \DateTime $updatedAt
	 *
	 * @return User
	 */
	public function setUpdatedAt(\DateTime $updatedAt): User {
		$this->updatedAt = $updatedAt;

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
	 * @return User
	 * @throws \Exception
	 */
	public function setImgFile( ?File $imgFile ): User {
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
	 * @return User
	 */
	public function setImgName( ?string $imgName ): User {
		$this->imgName = $imgName;
		return $this;
	}

    /**
     * @return Collection|Platform[]
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platforms->contains($platform)) {
            $this->platforms->removeElement($platform);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }
	/**
	 * String representation of object
	 * @link https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 * @since 5.1.0
	 */
	public function serialize() {
		return serialize([
			$this->id,
			$this->username,
			$this->password
		]);
	}

	/**
	 * Constructs the object
	 * @link https://php.net/manual/en/serializable.unserialize.php
	 *
	 * @param string $serialized <p>
	 * The string representation of the object.
	 * </p>
	 *
	 * @return void
	 * @since 5.1.0
	 */
	public function unserialize($serialized) {
		[
			$this->id,
			$this->username,
			$this->password
		] = unserialize($serialized, ['allowed_classes' => false]);
	}

	/**
	 * @return string|null
	 */
	public function getCity(): ?string {
		return $this->city;
	}

	/**
	 * @param string|null $city
	 *
	 * @return User
	 */
	public function setCity(?string $city): User {
		$this->city = $city;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getPostalCode(): ?int {
		return $this->postalCode;
	}

	/**
	 * @param int|null $postalCode
	 *
	 * @return User
	 */
	public function setPostalCode(?int $postalCode): User {
		$this->postalCode = $postalCode;

		return $this;
	}

    /**
     * @return float
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     * @return User
     */
    public function setLat(float $lat): User
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return float
     */
    public function getLon(): ?float
    {
        return $this->lon;
    }

    /**
     * @param float $lon
     * @return User
     */
    public function setLon(float $lon): User
    {
        $this->lon = $lon;
        return $this;
    }




}
