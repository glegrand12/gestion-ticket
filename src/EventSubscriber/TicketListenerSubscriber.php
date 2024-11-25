<?php
namespace App\EventSubscriber;

use App\Entity\TicketStatusHistory;
use App\Event\TicketCreatedEvent;
use App\Event\TicketStatusUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TicketListenerSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            //TicketCreatedEvent::class => 'onTicketCreated',
            TicketStatusUpdatedEvent::class => 'onTicketStatusUpdated',
        ];
    }



    public function onTicketStatusUpdated(TicketStatusUpdatedEvent $event): void
    {
        $ticket = $event->getTicket();

        $statusHistory = new TicketStatusHistory();
        $statusHistory->setTicketId($ticket);
        $statusHistory->setStatus($ticket->getStatus());
        $statusHistory->setChangedAt(new \DateTimeImmutable());

        $user = $this->security->getUser();
        if ($user) {
            $statusHistory->setChangedBy($user);
        }

        $this->entityManager->persist($statusHistory);
        $this->entityManager->flush();
    }
}
