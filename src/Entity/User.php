<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255)
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
    public function __construct()
    {
        $this->adverts = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->commentsCreated = new ArrayCollection();
        $this->commentsReceived = new ArrayCollection();
        $this->rating = 0;
        $this->coins = 0;
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
    public function setPassword(string $password): self
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
}