<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Enum\TicketStatusType;
use App\Repository\TicketStatusHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: TicketStatusHistoryRepository::class)]
class TicketStatusHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Tickets::class, inversedBy: 'statusHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tickets $ticket = null;

    #[ORM\Column(type: 'string', enumType: TicketStatusType::class)]
    private ?TicketStatusType $status = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $changed_by = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $changed_at;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketId(): ?Tickets
    {
        return $this->ticket;
    }

    public function setTicketId(?Tickets $ticket): void
    {
        $this->ticket = $ticket;
    }

    public function getStatus(): TicketStatusType
    {
        return $this->status;
    }

    public function setStatus(TicketStatusType $status): void
    {
        $this->status = $status;
    }

    public function getChangedBy(): Users
    {
        return $this->changed_by;
    }

    public function setChangedBy(Users $changed_by): void
    {
        $this->changed_by = $changed_by;
    }

    public function getChangedAt(): \DateTimeInterface
    {
        return $this->changed_at;
    }

    public function setChangedAt(\DateTimeInterface $changed_at): void
    {
        $this->changed_at = $changed_at;
    }


}
