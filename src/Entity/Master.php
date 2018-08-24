<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass="App\Repository\MasterRepository")
 */
class Master implements UserInterface
{
    /**
     * @Groups("master")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("master")
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @Groups("master")
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @Groups("master",)
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Groups("master")
     * @ORM\Column(type="string", length=255)
     */
    private $apiKey;

    /**
     * @Groups("master")
     * @ORM\OneToOne(targetEntity="App\Entity\Company", mappedBy="master", cascade={"persist", "remove"})
     */
    private $company;

    /**
     * @Groups("master")
     * @ORM\Column(type="simple_array")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->apiKey = uniqid('', true);

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        // set (or unset) the owning side of the relation if necessary
        $newMaster = $company === null ? null : $this;
        if ($newMaster !== $company->getMaster()) {
            $company->setMaster($newMaster);
        }

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
