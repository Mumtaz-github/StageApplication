<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use App\Repository\JourFerieRepository;
use App\Service\DateLibrary\DateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
// CONTROLLER
#[Route('/', name: 'app_planning')]
public function index(
    FormationRepository $formationRepository,
    JourFerieRepository $jourFerieRepository,
    DateService $dateService
): Response {
    $formations = $formationRepository->findAllWithRelations();
    $today = new \DateTime();

    // Calculate date range
    $startDate = new \DateTime('2024-01-01');
    $endDate = new \DateTime('2025-12-31');

    foreach ($formations as $f) {
        $startDate = min($startDate, $f->getDateDebut());
        $endDate = max($endDate, $f->getDateFin());
    }

    // Get date components
    $months = $dateService->getMonthsBetweenDates($startDate, $endDate);
    
    $daysInMonths = [];
    $monthsInYear = [];
    foreach ($months as $month) {
        $year = explode('-', $month)[0];
        $days = date('t', strtotime($month . '-01'));
        $daysInMonths[$month] = $days;
        $monthsInYear[$year] = ($monthsInYear[$year] ?? 0) + 1;
    }

    // Group formations
    $groupedFormations = [];
    foreach ($formations as $f) {
        $group = $f->getGroupeRattachement() ?? 'Non groupé';
        $groupedFormations[$group][] = $f;
    }

    // Get holidays with their full data
$holidays = $jourFerieRepository->findBetweenDates($startDate, $endDate);
    $currentDayOffset = $dateService->getOffsetDaysBetween($startDate, $today);

    return $this->render('planning/index.html.twig', [
        'grouped_formations' => $groupedFormations,
        'holidays' => $holidays, // Pass full entities
        'months' => $months,
        'days_in_months' => $daysInMonths,
        'months_in_year' => $monthsInYear,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'dayWidth' => 2,
        'current_day_position' => $currentDayOffset * 2,
        'date_service' => $dateService
    ]);
}
}