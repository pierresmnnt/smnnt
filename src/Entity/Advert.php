<?php

namespace App\Entity;

use App\Repository\AdvertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdvertRepository::class)]
class Advert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $advertiser = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Url]
    private ?string $landingPage = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $cta = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $logo = null;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: Adslot::class)]
    private Collection $adslots;

    #[ORM\Column]
    private ?bool $active = false;

    public function __construct()
    {
        $this->adslots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCta(): ?string
    {
        return $this->cta;
    }

    public function setCta(string $cta): self
    {
        $this->cta = $cta;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdvertiser(): ?string
    {
        return $this->advertiser;
    }

    public function setAdvertiser(string $advertiser): self
    {
        $this->advertiser = $advertiser;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, Adslot>
     */
    public function getAdslots(): Collection
    {
        return $this->adslots;
    }

    public function addAdslot(Adslot $adslot): self
    {
        if (!$this->adslots->contains($adslot)) {
            $this->adslots->add($adslot);
            $adslot->setAdvert($this);
        }

        return $this;
    }

    public function removeAdslot(Adslot $adslot): self
    {
        if ($this->adslots->removeElement($adslot)) {
            // set the owning side to null (unless already changed)
            if ($adslot->getAdvert() === $this) {
                $adslot->setAdvert(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLandingPage(): ?string
    {
        return $this->landingPage;
    }

    public function setLandingPage(?string $landingPage): self
    {
        $this->landingPage = $landingPage;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
