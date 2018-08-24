<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @Groups({"company", "master"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"company", "master"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups("company")
     * @ORM\Column(type="string", length=255)
     */
    private $slogan;

    /**
     * @Groups("company")
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @Groups("company")
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @Groups("company")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webSiteUrl;

    /**
     * @Groups("company")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureUrl;

    /**
     * @Groups("company")
     * @ORM\OneToOne(targetEntity="App\Entity\Master", inversedBy="company", cascade={"persist", "remove"})
     */
    private $master;

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