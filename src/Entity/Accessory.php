<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AccessoryRepository")
 */
class Accessory extends Item
{

    # region Props
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $model;

    #endregion

    public function __construct()
    {
        parent::__construct();
    }

    # region Getters & Setters
    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }
    #endregion
}
