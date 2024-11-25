<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Enum\TicketStatusType;
use App\Form\TicketStatusUpdateType;
use App\Form\TicketsType;
use App\Repository\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Event\TicketCreatedEvent;
use App\Event\TicketStatusUpdatedEvent;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[IsGranted('ROLE_USER')]
#[Route('/tickets')]
final class TicketsController extends AbstractController
{
    #[Route(name: 'app_tickets_index', methods: ['GET'])]
    public function index(TicketsRepository $ticketsRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $tickets = $ticketsRepository->findAll();
        } elseif ($this->isGranted('ROLE_SUPPORT')) {
            $tickets = $ticketsRepository->findBy(['assignedTo' => $user]);
        } else {
            $tickets = $ticketsRepository->findBy(['createdBy' => $user]);
        }

        return $this->render('tickets/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }


    #[Route('/new', name: 'app_tickets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $ticket = new Tickets();
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime();
            $ticket->setCreatedAt($now);
            $ticket->setUpdatedAt($now);
            $ticket->setStatus(TicketStatusType::OUVERT);
            $ticket->setCreatedBy($this->getUser());
            $entityManager->persist($ticket);
            $entityManager->flush();
            //$eventDispatcher->dispatch(new TicketStatusUpdatedEvent($ticket));
            $eventDispatcher->dispatch(new TicketStatusUpdatedEvent($ticket));

            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tickets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tickets $ticket, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $ticket->getCreatedBy() !== $user) {
            throw new AccessDeniedException('You can only edit your own tickets.');
        }

        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit-status', name: 'app_ticket_edit_status', methods: ['GET', 'POST'])]
    public function editStatus(Request $request, Tickets $ticket, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher): Response
    {
        $user = $this->getUser();
        if ((!$this->isGranted('ROLE_SUPPORT') || $ticket->getAssignedTo() !== $user)) {
            throw new AccessDeniedException('You are not allowed to edit the status of this ticket.');
        }

        $form = $this->createForm(TicketStatusUpdateType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();
            $eventDispatcher->dispatch(new TicketStatusUpdatedEvent($ticket));

            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tickets/edit_status.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_tickets_show', methods: ['GET'])]
    public function show(Tickets $ticket): Response
    {
        $user = $this->getUser();

        if (
            !$this->isGranted('ROLE_ADMIN') &&
            (
                $ticket->getCreatedBy() !== $user &&
                $ticket->getAssignedTo() !== $user
            )
        ) {
            throw new AccessDeniedException('You are not allowed to view this ticket.');
        }

        $statusHistories = $ticket->getStatusHistories();

        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
            'history' => $statusHistories,
        ]);
    }
    #[Route('/{id}', name: 'app_tickets_delete', methods: ['POST'])]
    public function delete(Request $request, Tickets $ticket, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (
            !$this->isGranted('ROLE_ADMIN') &&
            $ticket->getCreatedBy() !== $user
        ) {
            throw new AccessDeniedException('You are not allowed to delete this ticket.');
        }

        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
    }


}

