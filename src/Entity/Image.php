<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $userEntity = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Event $eventEntity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function getUserEntity(): ?User
    {
        return $this->userEntity;
    }

    public function setUserEntity(?User $userEntity): static
    {
        $this->userEntity = $userEntity;

        return $this;
    }

    public function getEventEntity(): ?Event
    {
        return $this->eventEntity;
    }

    public function setEventEntity(?Event $eventEntity): static
    {
        $this->eventEntity = $eventEntity;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'slug' => $this->getSlug(),
            'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s') ?? null,
            'updatedAt' => $this->getUpdatedAt()?->format('Y-m-d H:i:s') ?? null,
            'deletedAt' => $this->getDeletedAt()?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
