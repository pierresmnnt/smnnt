<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $kicker;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    private $heroImageUrl = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $heroImageCredit = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private $content;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'boolean')]
    private $published = false;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    private $topics;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $publishedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\Column(nullable: true)]
    private ?bool $privateAccess = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $privateAccessToken = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->topics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getKicker(): ?string
    {
        return $this->kicker;
    }

    public function setKicker(string $kicker): self
    {
        $this->kicker = $kicker;

        return $this;
    }

    public function getHeroImageUrl(): ?string
    {
        return $this->heroImageUrl;
    }

    public function setHeroImageUrl(?string $heroImageUrl): self
    {
        $this->heroImageUrl = $heroImageUrl;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Category $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
        }

        return $this;
    }

    public function removeTopic(Category $topic): self
    {
        $this->topics->removeElement($topic);

        return $this;
    }

    public function getHeroImageCredit(): ?string
    {
        return $this->heroImageCredit;
    }

    public function setHeroImageCredit(?string $heroImageCredit): self
    {
        $this->heroImageCredit = $heroImageCredit;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(PreUpdateEventArgs $event): void
    {
        if($this->getPublishedAt() && $this->getPublished()){
            if($event->hasChangedField('title') || $event->hasChangedField('kicker')|| $event->hasChangedField('heroImageUrl')|| $event->hasChangedField('content')) {
                $this->updatedAt = new DateTimeImmutable();
            }
        }
    }

    public function isPrivateAccess(): ?bool
    {
        return $this->privateAccess;
    }

    public function setPrivateAccess(?bool $privateAccess): self
    {
        $this->privateAccess = $privateAccess;

        return $this;
    }

    public function getPrivateAccessToken(): ?string
    {
        return $this->privateAccessToken;
    }

    public function setPrivateAccessToken(?string $privateAccessToken): self
    {
        $this->privateAccessToken = $privateAccessToken;

        return $this;
    }
}
