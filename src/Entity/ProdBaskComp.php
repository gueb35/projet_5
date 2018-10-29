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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $member_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameProd;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kgOrUntity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityProd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemberId(): ?int
    {
        return $this->member_id;
    }

    public function setMemberId(?int $member_id): self
    {
        $this->member_id = $member_id;

        return $this;
    }

    public function getNameProd(): ?string
    {
        return $this->nameProd;
    }

    public function setNameProd(?string $nameProd): self
    {
        $this->nameProd = $nameProd;

        return $this;
    }

    public function getKgOrUntity(): ?string
    {
        return $this->kgOrUntity;
    }

    public function setKgOrUntity(?string $kgOrUntity): self
    {
        $this->kgOrUntity = $kgOrUntity;

        return $this;
    }

    public function getQuantityProd(): ?int
    {
        return $this->quantityProd;
    }

    public function setQuantityProd(?int $quantityProd): self
    {
        $this->quantityProd = $quantityProd;

        return $this;
    }
}
