<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            'normalization_context' => ['groups' => ['collection:read']],
            ]
    ],
    itemOperations: [
        "get"
    ],
    normalizationContext: ['groups' => ['image:read']],
    denormalizationContext: ['groups' => ['image:write']],
    attributes: ["order" => ["date" => "DESC"]],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'albums' => 'exact'])]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["image:read", "collection:read"])]
    private $id;

    #[Vich\UploadableField(mapping: "images", fileNameProperty: "imageName")]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["image:read", "collection:read"])]
    private $imageName;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'images')]
    #[Groups(["image:read", "collection:read"])]
    private $albums;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["image:read", "collection:read"])]
    private $alt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups("image:read")]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups("image:read")]
    private $exposure;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups("image:read")]
    private $aperture;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups("image:read")]
    private $iso;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups("image:read")]
    private $focal;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups("image:read")]
    private $date;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isInPortfolio = true;

    #[ORM\ManyToOne(targetEntity: Gear::class)]
    #[Groups("image:read")]
    private $gearCamera;

    #[ORM\ManyToOne(targetEntity: Gear::class)]
    #[Groups("image:read")]
    private $gearLens;

    #[Groups(["image:read", "collection:read"])]
    private $contentUrl;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->albums = new ArrayCollection();
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
    */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbums(Category $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
        }

        return $this;
    }

    public function removeAlbums(Category $album): self
    {
        $this->albums->removeElement($album);

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExposure(): ?string
    {
        return $this->exposure;
    }

    public function getExposureString(): ?string
    {
        return $this->exposure . "s";
    }

    public function setExposure(?string $exposure): self
    {
        $this->exposure = $exposure;

        return $this;
    }

    public function getAperture(): ?string
    {
        return $this->aperture;
    }

    public function getApertureString(): ?string
    {
        return "f/" . $this->aperture;
    }

    public function setAperture(?string $aperture): self
    {
        $this->aperture = $aperture;

        return $this;
    }

    public function getIso(): ?int
    {
        return $this->iso;
    }

    public function setIso(?int $iso): self
    {
        $this->iso = $iso;

        return $this;
    }

    public function getFocal(): ?int
    {
        return $this->focal;
    }

    public function getFocalString(): ?string
    {
        return $this->focal . "mm";
    }

    public function setFocal(?int $focal): self
    {
        $this->focal = $focal;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsInPortfolio(): ?bool
    {
        return $this->isInPortfolio;
    }

    public function setIsInPortfolio(?bool $isInPortfolio): self
    {
        $this->isInPortfolio = $isInPortfolio;

        return $this;
    }

    public function getGearCamera(): ?Gear
    {
        return $this->gearCamera;
    }

    public function setGearCamera(?Gear $gearCamera): self
    {
        $this->gearCamera = $gearCamera;

        return $this;
    }

    public function getGearLens(): ?Gear
    {
        return $this->gearLens;
    }

    public function setGearLens(?Gear $gearLens): self
    {
        $this->gearLens = $gearLens;

        return $this;
    }

    public function getContentUrl(): string
    {
        if($this->contentUrl === null) {
            throw new \LogicException('this field has not been initialized');
        }

        return $this->contentUrl;
    }

    public function setContentUrl(string $contentUrl)
    {
        $this->contentUrl = $contentUrl;
    }
}
