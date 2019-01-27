<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlatformRepository")
 */
class Platform
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GamePlatform", mappedBy="platform")
     */
    private $gamePlatforms;


    public function __construct()
    {
        $this->gamePlatforms = new ArrayCollection();
    }

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
            $gamePlatform->setPlatform($this);
        }

        return $this;
    }

    public function removeGamePlatform(GamePlatform $gamePlatform): self
    {
        if ($this->gamePlatforms->contains($gamePlatform)) {
            $this->gamePlatforms->removeElement($gamePlatform);
            // set the owning side to null (unless already changed)
            if ($gamePlatform->getPlatform() === $this) {
                $gamePlatform->setPlatform(null);
            }
        }

        return $this;
    }





}
