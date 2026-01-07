<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $roleEntity = null;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'userEntity', cascade: ['persist', 'remove'])]
    private Collection $images;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'users')]
    private Collection $events;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'author')]
    private Collection $eventsOwned;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'author')]
    private Collection $imagesOwned;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->eventsOwned = new ArrayCollection();
        $this->imagesOwned = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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

    public function getRoleEntity(): ?Role
    {
        return $this->roleEntity;
    }

    public function setRoleEntity(?Role $roleEntity): static
    {
        $this->roleEntity = $roleEntity;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeUser($this);
        }

        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setUserEntity($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getUserEntity() === $this) {
                $image->setUserEntity(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->roleEntity) {
            $roles[] = $this->roleEntity->getName();
        }
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventsOwned(): Collection
    {
        return $this->eventsOwned;
    }

    public function addEventsOwned(Event $eventsOwned): static
    {
        if (!$this->eventsOwned->contains($eventsOwned)) {
            $this->eventsOwned->add($eventsOwned);
            $eventsOwned->setAuthor($this);
        }

        return $this;
    }

    public function removeEventsOwned(Event $eventsOwned): static
    {
        if ($this->eventsOwned->removeElement($eventsOwned)) {
            // set the owning side to null (unless already changed)
            if ($eventsOwned->getAuthor() === $this) {
                $eventsOwned->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImagesOwned(): Collection
    {
        return $this->imagesOwned;
    }

    public function addImagesOwned(Image $imagesOwned): static
    {
        if (!$this->imagesOwned->contains($imagesOwned)) {
            $this->imagesOwned->add($imagesOwned);
            $imagesOwned->setAuthor($this);
        }

        return $this;
    }

    public function removeImagesOwned(Image $imagesOwned): static
    {
        if ($this->imagesOwned->removeElement($imagesOwned)) {
            // set the owning side to null (unless already changed)
            if ($imagesOwned->getAuthor() === $this) {
                $imagesOwned->setAuthor(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        $imagesArray = [];
        $images = $this->getImages();
        if (is_iterable($images)) {
            foreach ($images as $image) {
                $imagesArray[] = $image?->toArray();
            }
        }

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'role' => $this->getRoleEntity()?->toArray(),
            'images' => $imagesArray,
            'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s') ?? null,
            'updatedAt' => $this->getUpdatedAt()?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
