<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @Vich\Uploadable()

 */
class Game
{
    #region Props

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
	 * @var \DateTime
	 * @ORM\Column(type="datetime", name="updated_at", nullable=true)
	 */
	private $updatedAt;

	/**
	 * @var File|null
	 * @Vich\UploadableField(mapping="game_image", fileNameProperty="imgName")
	 */
	private $imgFile;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255)
	 */
	private $imgName;


	

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $multiplayer;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GamePlatform", mappedBy="game", fetch="EAGER")
     */
    private $gamePlatforms;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $slug;

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    #endregion

    public function __construct()
    {
    	$this->updatedAt = new \DateTime('now');
        $this->gamePlatforms = new ArrayCollection();
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


	/**
	 * @return File|null
	 */
	public function getImgFile(): ?File {
		return $this->imgFile;
	}

	/**
	 * @param File|null $imgFile
	 *
	 * @return Game
	 * @throws \Exception
	 */
	public function setImgFile( ?File $imgFile ): Game {
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
	 * @return Game
	 */
	public function setImgName( ?string $imgName ): Game {
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
            $gamePlatform->setGame($this);
        }

        return $this;
    }

    public function removeGamePlatform(GamePlatform $gamePlatform): self
    {
        if ($this->gamePlatforms->contains($gamePlatform)) {
            $this->gamePlatforms->removeElement($gamePlatform);
            // set the owning side to null (unless already changed)
            if ($gamePlatform->getGame() === $this) {
                $gamePlatform->setGame(null);
            }
        }

        return $this;
    }

    #endregion
}
