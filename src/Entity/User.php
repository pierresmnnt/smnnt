<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private $password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $contactEmail;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $socialInstagram;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $socialGithub;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $socialLinkedin;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $socialTwitter;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): self
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getSocialInstagram(): ?string
    {
        return $this->socialInstagram;
    }

    public function setSocialInstagram(?string $socialInstagram): self
    {
        $this->socialInstagram = $socialInstagram;

        return $this;
    }

    public function getSocialGithub(): ?string
    {
        return $this->socialGithub;
    }

    public function setSocialGithub(?string $socialGithub): self
    {
        $this->socialGithub = $socialGithub;

        return $this;
    }

    public function getSocialLinkedin(): ?string
    {
        return $this->socialLinkedin;
    }

    public function setSocialLinkedin(?string $socialLinkedin): self
    {
        $this->socialLinkedin = $socialLinkedin;

        return $this;
    }

    public function getSocialTwitter(): ?string
    {
        return $this->socialTwitter;
    }

    public function setSocialTwitter(?string $socialTwitter): self
    {
        $this->socialTwitter = $socialTwitter;

        return $this;
    }
}
