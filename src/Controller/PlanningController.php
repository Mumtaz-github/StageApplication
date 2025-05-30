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
        // Get all formations with their relations
        $formations = $formationRepository->findAllWithRelations();
        
        // Calculate date range
        $startDate = count($formations) > 0 ? 
            min(array_map(fn($f) => $f->getDateDebut(), $formations)) : 
            new \DateTime();
        $endDate = count($formations) > 0 ? 
            max(array_map(fn($f) => $f->getDateFin(), $formations)) : 
            new \DateTime('+1 month');

        // Get months between dates
        $months = $dateService->getMonthsBetweenDates($startDate, $endDate);

        // Calculate days per month and months per year
        $daysInMonths = [];
        $monthsInYear = [];
        
        foreach ($months as $month) {
            $days = date('t', strtotime($month . '-01'));
            $daysInMonths[$month] = $days;
            
            $year = explode('-', $month)[0];
            $monthsInYear[$year] = ($monthsInYear[$year] ?? 0) + $days;
        }

        // Get holidays
        $currentYear = (new \DateTime())->format('Y');
        $holidays = $jourFerieRepository->findBy([
            'annee' => $currentYear,
            'zone' => 'metropole'
        ]);

        return $this->render('planning/index.html.twig', [
            'formations' => $formations,
            'holidays' => array_map(fn($h) => $h->getDate()->format('Y-m-d'), $holidays),
            'months' => $months,
            'days_in_months' => $daysInMonths,
            'months_in_year' => $monthsInYear,
            'start_date' => $startDate,
            'dayWidth' => 2, // pixels per day
            'date_service' => $dateService
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
