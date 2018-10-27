<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BasketCompRepository")
 */
class BasketComp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $member_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_3;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_4;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_5;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_6;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_6;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_7;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_7;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_8;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_8;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_9;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_9;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_10;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unity_prod_10;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_11;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_11;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_12;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_12;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_13;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_13;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_14;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_14;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_15;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_15;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_16;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_16;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_17;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_17;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_18;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_18;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_19;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_19;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_prod_20;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kg_prod_20;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemberId(): ?int
    {
        return $this->member_id;
    }

    public function setMemberId(int $member_id): self
    {
        $this->member_id = $member_id;

        return $this;
    }

    public function getNameProd1(): ?string
    {
        return $this->name_prod_1;
    }

    public function setNameProd1(?string $name_prod_1): self
    {
        $this->name_prod_1 = $name_prod_1;

        return $this;
    }

    public function getUnityProd1(): ?int
    {
        return $this->unity_prod_1;
    }

    public function setUnityProd1(?int $unity_prod_1): self
    {
        $this->unity_prod_1 = $unity_prod_1;

        return $this;
    }

    public function getNameProd2(): ?string
    {
        return $this->name_prod_2;
    }

    public function setNameProd2(?string $name_prod_2): self
    {
        $this->name_prod_2 = $name_prod_2;

        return $this;
    }

    public function getUnityProd2(): ?int
    {
        return $this->unity_prod_2;
    }

    public function setUnityProd2(?int $unity_prod_2): self
    {
        $this->unity_prod_2 = $unity_prod_2;

        return $this;
    }

    public function getNameProd3(): ?string
    {
        return $this->name_prod_3;
    }

    public function setNameProd3(?string $name_prod_3): self
    {
        $this->name_prod_3 = $name_prod_3;

        return $this;
    }

    public function getUnityProd3(): ?int
    {
        return $this->unity_prod_3;
    }

    public function setUnityProd3(?int $unity_prod_3): self
    {
        $this->unity_prod_3 = $unity_prod_3;

        return $this;
    }

    public function getNameProd4(): ?string
    {
        return $this->name_prod_4;
    }

    public function setNameProd4(?string $name_prod_4): self
    {
        $this->name_prod_4 = $name_prod_4;

        return $this;
    }

    public function getUnityProd4(): ?int
    {
        return $this->unity_prod_4;
    }

    public function setUnityProd4(?int $unity_prod_4): self
    {
        $this->unity_prod_4 = $unity_prod_4;

        return $this;
    }

    public function getNameProd5(): ?string
    {
        return $this->name_prod_5;
    }

    public function setNameProd5(?string $name_prod_5): self
    {
        $this->name_prod_5 = $name_prod_5;

        return $this;
    }

    public function getUnityProd5(): ?int
    {
        return $this->unity_prod_5;
    }

    public function setUnityProd5(?int $unity_prod_5): self
    {
        $this->unity_prod_5 = $unity_prod_5;

        return $this;
    }

    public function getNameProd6(): ?string
    {
        return $this->name_prod_6;
    }

    public function setNameProd6(?string $name_prod_6): self
    {
        $this->name_prod_6 = $name_prod_6;

        return $this;
    }

    public function getUnityProd6(): ?int
    {
        return $this->unity_prod_6;
    }

    public function setUnityProd6(?int $unity_prod_6): self
    {
        $this->unity_prod_6 = $unity_prod_6;

        return $this;
    }

    public function getNameProd7(): ?string
    {
        return $this->name_prod_7;
    }

    public function setNameProd7(?string $name_prod_7): self
    {
        $this->name_prod_7 = $name_prod_7;

        return $this;
    }

    public function getUnityProd7(): ?int
    {
        return $this->unity_prod_7;
    }

    public function setUnityProd7(?int $unity_prod_7): self
    {
        $this->unity_prod_7 = $unity_prod_7;

        return $this;
    }

    public function getNameProd8(): ?string
    {
        return $this->name_prod_8;
    }

    public function setNameProd8(?string $name_prod_8): self
    {
        $this->name_prod_8 = $name_prod_8;

        return $this;
    }

    public function getUnityProd8(): ?int
    {
        return $this->unity_prod_8;
    }

    public function setUnityProd8(?int $unity_prod_8): self
    {
        $this->unity_prod_8 = $unity_prod_8;

        return $this;
    }

    public function getNameProd9(): ?string
    {
        return $this->name_prod_9;
    }

    public function setNameProd9(?string $name_prod_9): self
    {
        $this->name_prod_9 = $name_prod_9;

        return $this;
    }

    public function getUnityProd9(): ?int
    {
        return $this->unity_prod_9;
    }

    public function setUnityProd9(?int $unity_prod_9): self
    {
        $this->unity_prod_9 = $unity_prod_9;

        return $this;
    }

    public function getNameProd10(): ?string
    {
        return $this->name_prod_10;
    }

    public function setNameProd10(?string $name_prod_10): self
    {
        $this->name_prod_10 = $name_prod_10;

        return $this;
    }

    public function getUnityProd10(): ?int
    {
        return $this->unity_prod_10;
    }

    public function setUnityProd10(?int $unity_prod_10): self
    {
        $this->unity_prod_10 = $unity_prod_10;

        return $this;
    }

    public function getNameProd11(): ?string
    {
        return $this->name_prod_11;
    }

    public function setNameProd11(?string $name_prod_11): self
    {
        $this->name_prod_11 = $name_prod_11;

        return $this;
    }

    public function getKgProd11(): ?int
    {
        return $this->kg_prod_11;
    }

    public function setKgProd11(?int $kg_prod_11): self
    {
        $this->kg_prod_11 = $kg_prod_11;

        return $this;
    }

    public function getNameProd12(): ?string
    {
        return $this->name_prod_12;
    }

    public function setNameProd12(?string $name_prod_12): self
    {
        $this->name_prod_12 = $name_prod_12;

        return $this;
    }

    public function getKgProd12(): ?int
    {
        return $this->kg_prod_12;
    }

    public function setKgProd12(?int $kg_prod_12): self
    {
        $this->kg_prod_12 = $kg_prod_12;

        return $this;
    }

    public function getNameProd13(): ?string
    {
        return $this->name_prod_13;
    }

    public function setNameProd13(?string $name_prod_13): self
    {
        $this->name_prod_13 = $name_prod_13;

        return $this;
    }

    public function getKgProd13(): ?int
    {
        return $this->kg_prod_13;
    }

    public function setKgProd13(?int $kg_prod_13): self
    {
        $this->kg_prod_13 = $kg_prod_13;

        return $this;
    }

    public function getNameProd14(): ?string
    {
        return $this->name_prod_14;
    }

    public function setNameProd14(?string $name_prod_14): self
    {
        $this->name_prod_14 = $name_prod_14;

        return $this;
    }

    public function getKgProd14(): ?int
    {
        return $this->kg_prod_14;
    }

    public function setKgProd14(?int $kg_prod_14): self
    {
        $this->kg_prod_14 = $kg_prod_14;

        return $this;
    }

    public function getNameProd15(): ?string
    {
        return $this->name_prod_15;
    }

    public function setNameProd15(?string $name_prod_15): self
    {
        $this->name_prod_15 = $name_prod_15;

        return $this;
    }

    public function getKgProd15(): ?int
    {
        return $this->kg_prod_15;
    }

    public function setKgProd15(?int $kg_prod_15): self
    {
        $this->kg_prod_15 = $kg_prod_15;

        return $this;
    }

    public function getNameProd16(): ?string
    {
        return $this->name_prod_16;
    }

    public function setNameProd16(?string $name_prod_16): self
    {
        $this->name_prod_16 = $name_prod_16;

        return $this;
    }

    public function getKgProd16(): ?int
    {
        return $this->kg_prod_16;
    }

    public function setKgProd16(?int $kg_prod_16): self
    {
        $this->kg_prod_16 = $kg_prod_16;

        return $this;
    }

    public function getNameProd17(): ?string
    {
        return $this->name_prod_17;
    }

    public function setNameProd17(?string $name_prod_17): self
    {
        $this->name_prod_17 = $name_prod_17;

        return $this;
    }

    public function getKgProd17(): ?int
    {
        return $this->kg_prod_17;
    }

    public function setKgProd17(?int $kg_prod_17): self
    {
        $this->kg_prod_17 = $kg_prod_17;

        return $this;
    }

    public function getNameProd18(): ?string
    {
        return $this->name_prod_18;
    }

    public function setNameProd18(?string $name_prod_18): self
    {
        $this->name_prod_18 = $name_prod_18;

        return $this;
    }

    public function getKgProd18(): ?int
    {
        return $this->kg_prod_18;
    }

    public function setKgProd18(?int $kg_prod_18): self
    {
        $this->kg_prod_18 = $kg_prod_18;

        return $this;
    }

    public function getNameProd19(): ?string
    {
        return $this->name_prod_19;
    }

    public function setNameProd19(?string $name_prod_19): self
    {
        $this->name_prod_19 = $name_prod_19;

        return $this;
    }

    public function getKgProd19(): ?int
    {
        return $this->kg_prod_19;
    }

    public function setKgProd19(?int $kg_prod_19): self
    {
        $this->kg_prod_19 = $kg_prod_19;

        return $this;
    }

    public function getNameProd20(): ?string
    {
        return $this->name_prod_20;
    }

    public function setNameProd20(?string $name_prod_20): self
    {
        $this->name_prod_20 = $name_prod_20;

        return $this;
    }

    public function getKgProd20(): ?int
    {
        return $this->kg_prod_20;
    }

    public function setKgProd20(?int $kg_prod_20): self
    {
        $this->kg_prod_20 = $kg_prod_20;

        return $this;
    }
}
