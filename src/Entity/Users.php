<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\OneToMany(targetEntity: Tickets::class, mappedBy: 'user')]
    private ?Collection $ticket_id = null;

    #[ORM\OneToMany(targetEntity: TicketStatusHistory::class, mappedBy: 'changed_by')]
    private Collection $ticketStatusHistory;

    public function getTicketId(): ?Collection
    {
        return $this->ticket_id;
    }

    public function setTicketId(?Collection $ticket_id): void
    {
        $this->ticket_id = $ticket_id;
    }

    public function getTicketStatusHistory(): Collection
    {
        return $this->ticketStatusHistory;
    }

    public function setTicketStatusHistory(Collection $ticketStatusHistory): void
    {
        $this->ticketStatusHistory = $ticketStatusHistory;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }


}
