<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProdBaskCompRepository")
 */
class ProdBaskComp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Members", inversedBy="prodBaskComps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $members;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameProd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $kgOrUnity;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantityProd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMembers(): ?Members
    {
        return $this->members;
    }

    public function setMembers(?Members $members): self
    {
        $this->members = $members;

        return $this;
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

    public function getKgOrUnity(): ?string
    {
        return $this->kgOrUnity;
    }

    public function setKgOrUnity(string $kgOrUnity): self
    {
        $this->kgOrUnity = $kgOrUnity;

        return $this;
    }

    public function getQuantityProd(): ?int
    {
        return $this->quantityProd;
    }

    public function setQuantityProd(int $quantityProd): self
    {
        $this->quantityProd = $quantityProd;

        return $this;
    }
}
