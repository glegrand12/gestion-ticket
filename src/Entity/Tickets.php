<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Enum\TicketPriorityType;
use App\Enum\TicketStatusType;
use App\Repository\TicketsRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketsRepository::class)]
class Tickets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(enumType: TicketStatusType::class)]
    private ?TicketStatusType $status = null;

    #[ORM\Column(enumType: TicketPriorityType::class)]
    private ?TicketPriorityType $priority = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Users $assignedTo = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Users $createdBy = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $deadline = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updated_at;

    #[ORM\OneToMany(targetEntity: TicketStatusHistory::class, mappedBy: 'ticket')]
    private Collection $statusHistories;

    public function __construct()
    {
        $this->statusHistories = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getStatus(): ?TicketStatusType
    {
        return $this->status;
    }

    public function setStatus(?TicketStatusType $status): void
    {
        $this->status = $status;
    }

    public function getPriority(): ?TicketPriorityType
    {
        return $this->priority;
    }

    public function setPriority(?TicketPriorityType $priority): void
    {
        $this->priority = $priority;
    }

    public function getAssignedTo(): ?Users
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?Users $assignedTo): void
    {
        $this->assignedTo = $assignedTo;
    }

    public function getCreatedBy(): ?Users
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Users $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeInterface $deadline): void
    {
        $this->deadline = $deadline;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getStatusHistories(): Collection
    {
        return $this->statusHistories;
    }

    public function setStatusHistories(Collection $statusHistories): void
    {
        $this->statusHistories = $statusHistories;
    }

}
