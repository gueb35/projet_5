<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MembersRepository")
 * @UniqueEntity(
 * fields= {"email"},
 * message= "L'email que vous avez indiqué est déjà utilisé !")
 */
class Members implements UserInterface
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
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $basketType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberBasketCollected;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberBasketCompouned;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dayOfWeek;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Votre mot de passe et la confirmation de votre mot de passe doivent être identiques")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProdBaskComp", mappedBy="members")
     */
    private $prodOfMembers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $basketTypeBis;

    public function __construct()
    {
        $this->prodOfMembers = new ArrayCollection();
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBasketType(): ?string
    {
        return $this->basketType;
    }

    public function setBasketType(?string $basketType): self
    {
        $this->basketType = $basketType;

        return $this;
    }

    public function getBasketTypeBis(): ?string
    {
        return $this->basketTypeBis;
    }

    public function setBasketTypeBis(?string $basketTypeBis): self
    {
        $this->basketTypeBis = $basketTypeBis;

        return $this;
    }

    public function getNumberBasketCollected(): ?int
    {
        return $this->numberBasketCollected;
    }

    public function setNumberBasketCollected(int $numberBasketCollected): self
    {
        $this->numberBasketCollected = $numberBasketCollected;

        return $this;
    }

    public function getNumberBasketCompouned(): ?int
    {
        return $this->numberBasketCompouned;
    }

    public function setNumberBasketCompouned(int $numberBasketCompouned): self
    {
        $this->numberBasketCompouned = $numberBasketCompouned;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(?string $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function eraseCredentials(){}

    public function getSalt(){}

    public function getRoles(){
        return ['ROLE_USER'];
    }

    /**
     * @return Collection|ProdBaskComp[]
     */
    public function getProdOfMembers(): Collection
    {
        return $this->prodOfMembers;
    }

    public function addProdOfMember(ProdBaskComp $prodOfMember): self
    {
        if (!$this->prodOfMembers->contains($prodOfMember)) {
            $this->prodOfMembers[] = $prodOfMember;
            $prodOfMember->setMembers($this);
        }

        return $this;
    }

    public function removeProdOfMember(ProdBaskComp $prodOfMember): self
    {
        if ($this->prodOfMembers->contains($prodOfMember)) {
            $this->prodOfMembers->removeElement($prodOfMember);
            // set the owning side to null (unless already changed)
            if ($prodOfMember->getMembers() === $this) {
                $prodOfMember->setMembers(null);
            }
        }

        return $this;
    }
}
