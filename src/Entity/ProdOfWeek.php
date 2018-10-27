<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProdOfWeekRepository")
 */
class ProdOfWeek
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prodByUnity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityProdUnity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prodByKg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityProdKg;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProdByUnity(): ?string
    {
        return $this->prodByUnity;
    }

    public function setProdByUnity(?string $prodByUnity): self
    {
        $this->prodByUnity = $prodByUnity;

        return $this;
    }

    public function getQuantityProdUnity(): ?int
    {
        return $this->quantityProdUnity;
    }

    public function setQuantityProdUnity(?int $quantityProdUnity): self
    {
        $this->quantityProdUnity = $quantityProdUnity;

        return $this;
    }

    public function getProdByKg(): ?string
    {
        return $this->prodByKg;
    }

    public function setProdByKg(?string $prodByKg): self
    {
        $this->prodByKg = $prodByKg;

        return $this;
    }

    public function getQuantityProdKg(): ?int
    {
        return $this->quantityProdKg;
    }

    public function setQuantityProdKg(?int $quantityProdKg): self
    {
        $this->quantityProdKg = $quantityProdKg;

        return $this;
    }
}
