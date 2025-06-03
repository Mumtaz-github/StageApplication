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
    #[Route('/', name: 'app_planning')]
    public function index(
        FormationRepository $formationRepository,
        JourFerieRepository $jourFerieRepository,
        DateService $dateService
    ): Response {
        // 1. Get formations with optimized query
        $formations = $formationRepository->findAllWithRelations();
        
        // 2. Calculate 24-month date range as per cahier des charges
        $today = new \DateTime();
        $startDate = $today;
        $endDate = (clone $today)->modify('+24 months');
        
        // 3. Adjust range if formations exist outside this window
        if (!empty($formations)) {
            $minDate = min(array_map(fn($f) => $f->getDateDebut(), $formations));
            $maxDate = max(array_map(fn($f) => $f->getDateFin(), $formations));
            
            $startDate = min($startDate, $minDate);
            $endDate = max($endDate, $maxDate);
        }

        // 4. Get months and holidays data
        $months = $dateService->getMonthsBetweenDates($startDate, $endDate);
        
        // 5. Calculate days per month and months per year
        $daysInMonths = [];
        $monthsInYear = [];
        
        foreach ($months as $month) {
            $year = explode('-', $month)[0];
            $days = date('t', strtotime($month . '-01'));
            
            $daysInMonths[$month] = $days;
            $monthsInYear[$year] = ($monthsInYear[$year] ?? 0) + $days;
        }

        // 6. Get holidays for the entire date range
        $holidays = $jourFerieRepository->findForDateRange($startDate, $endDate);

        return $this->render('planning/index.html.twig', [
            'formations' => $formations,
            'holidays' => array_map(fn($h) => $h->getDate(), $holidays),
            'months' => $months,
            'days_in_months' => $daysInMonths,
            'months_in_year' => $monthsInYear, // Add this missing variable
            'start_date' => $startDate,
            'dayWidth' => 2, // pixels per day
            'date_service' => $dateService,
            'current_year' => $today->format('Y')
        ]);
    }
}




















// namespace App\Controller;

// use App\Repository\FormationRepository;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class PlanningController extends AbstractController
// {
//     #[Route('/', name: 'app_planning')]
//     public function index(FormationRepository $formationRepository): Response
//     {
//         $formations = $formationRepository->findAll();

//         return $this->render('planning/index.html.twig', [
//             'formations' => $formations,
//         ]);
//     }
// }
