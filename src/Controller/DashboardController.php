<?php

namespace App\Controller;

use App\Entity\Users;
use App\Enum\TicketPriorityType;
use App\Enum\TicketStatusType;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(TicketsRepository $ticketsRepository, ChartBuilderInterface $chartBuilder): Response
    {
        /** @var Users $user */
        $user = $this->getUser();


        $statusCounts = [];
        foreach (TicketStatusType::cases() as $status) {
            $count = $ticketsRepository->count(['status' => $status]);
            $statusCounts[] = [
                'label' => $status->label(),
                'count' => $count,
            ];
        }

        $priorityCounts = [];
        foreach (TicketPriorityType::cases() as $priority) {
            $count = $ticketsRepository->count(['priority' => $priority]);
            $priorityCounts[] = [
                'label' => $priority->label(),
                'count' => $count,
            ];
        }

        $userTickets = $ticketsRepository->findBy(['assignedTo' => $user->getId()]);

        if ($user->isAdmin()) {
            $allTickets = $ticketsRepository->findAll();
        }
        $statusChart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $statusChart->setData([
            'labels' => array_column($statusCounts, 'label'),
            'datasets' => [
                [
                    'label' => 'Ticket Status',
                    'data' => array_column($statusCounts, 'count'),
                    'backgroundColor' => ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                ],
            ],
        ]);
        $priorityChart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $priorityChart->setData([
            'labels' => array_column($priorityCounts, 'label'),
            'datasets' => [
                [
                    'label' => 'Priorité des Tickets',
                    'data' => array_column($priorityCounts, 'count'),
                    'backgroundColor' => ['#17a2b8', '#ffc107', '#dc3545'],
                ],
            ],
        ]);
        $priorityChart->setOptions([
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ]);


        $username = $user->getEmail();
        $username = explode('@', $username);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $user,
            'userTickets' => $userTickets,
            'statusChart' => $statusChart,
            'priorityChart' => $priorityChart,
            'allTickets' => $allTickets ?? null,
            'username' => $username[0],
        ]);
    }

    #[Route('/', name: 'home')]
    public function index(): RedirectResponse
    {
        // Redirige toujours vers /dashboard
        return $this->redirectToRoute('app_dashboard');
    }
}
