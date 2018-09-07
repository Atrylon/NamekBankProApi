<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @Groups({"company", "master", "creditcard"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     * @Groups({"company", "master", "creditcard"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     * @Groups("company")
     * @ORM\Column(type="string", length=255)
     */
    private $slogan;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 8, max = 20, minMessage = "min_lenght", maxMessage = "max_lenght")
     * @Groups("company")
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     * @Groups("company")
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @Assert\Url()
     * @Groups("company")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webSiteUrl;

    /**
     * @Assert\Url()
     * @Groups("company")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureUrl;

    /**
     * @Groups("company")
     * @ORM\OneToOne(targetEntity="App\Entity\Master", inversedBy="company", cascade={"persist"})
     */
    private $master;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Creditcard", mappedBy="company", cascade={"persist", "remove"})
     */
    private $creditcards;

    /**
     * @return mixed
     */
    public function getCreditcards()
    {
        return $this->creditcards;
    }

    /**
     * @param mixed $creditcards
     */
    public function setCreditcards($creditcards)
    {
        $this->creditcards = $creditcards;
    }

    public function __construct()
    {
        $this->creditcards = new ArrayCollection();
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

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getWebSiteUrl(): ?string
    {
        return $this->webSiteUrl;
    }

    public function setWebSiteUrl(?string $webSiteUrl): self
    {
        $this->webSiteUrl = $webSiteUrl;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    public function getMaster(): ?Master
    {
        return $this->master;
    }

    public function setMaster(?Master $master): self
    {
        $this->master = $master;

        return $this;
    }

}
