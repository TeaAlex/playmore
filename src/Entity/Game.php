<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game extends Item
{
    #region Props

    /**
     * @ORM\Column(type="datetime")
     */
    protected $releaseDate;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $multiplayer;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editor", inversedBy="games")
     */
    private $editor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="games")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classification", inversedBy="games")
     */
    private $classification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Developper", inversedBy="games")
     */
    private $developper;



    #endregion

    public function __construct()
    {
        parent::__construct();
    }

    # region Getters & Setters

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getMultiplayer(): ?bool
    {
        return $this->multiplayer;
    }

    public function setMultiplayer(bool $multiplayer): self
    {
        $this->multiplayer = $multiplayer;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEditor(): ?Editor
    {
        return $this->editor;
    }

    public function setEditor(?Editor $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getClassification(): ?Classification
    {
        return $this->classification;
    }

    public function setClassification(?Classification $classification): self
    {
        $this->classification = $classification;

        return $this;
    }

    public function getDevelopper(): ?Developper
    {
        return $this->developper;
    }

    public function setDevelopper(?Developper $developper): self
    {
        $this->developper = $developper;

        return $this;
    }

    #endregion
}
