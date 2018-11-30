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
     * @ORM\Column(type="string", length=255)
     */
    private $nameProd;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $saleType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProd(): ?string
    {
        return $this->nameProd;
    }

    public function setNameProd(string $nameProd): self
    {
        $this->nameProd = $nameProd;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSaleType(): ?string
    {
        return $this->saleType;
    }

    public function setSaleType(string $saleType): self
    {
        $this->saleType = $saleType;

        return $this;
    }
}
