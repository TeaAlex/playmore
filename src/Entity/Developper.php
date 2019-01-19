<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DevelopperRepository")
 * @Vich\Uploadable()
 */
class Developper
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
     * @var File|null
     * @Vich\UploadableField(mapping="dev_image", fileNameProperty="imgName")
     */
    protected $imgFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imgName;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="developper")
     */
    private $games;

	/**
	 * @ORM\Column(type="datetime", name="updated_at", options={"default": "CURRENT_TIMESTAMP"})
	 */
	private $updatedAt;



    public function __construct()
    {
        $this->games = new ArrayCollection();
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
     * @return File|null
     */
    public function getImgFile(): ?File {
        return $this->imgFile;
    }

    /**
     * @param File|null $imgFile
     *
     * @return Developper
     * @throws \Exception
     */
    public function setImgFile( ?File $imgFile ): Developper {
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
     * @return Developper
     */
    public function setImgName( ?string $imgName ): Developper {
        $this->imgName = $imgName;
        return $this;
    }





    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setDevelopper($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getDevelopper() === $this) {
                $game->setDevelopper(null);
            }
        }

        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	/**
	 * @param mixed $updatedAt
	 *
	 * @return Developper
	 */
	public function setUpdatedAt( $updatedAt ) {
		$this->updatedAt = $updatedAt;

		return $this;
	}


}
