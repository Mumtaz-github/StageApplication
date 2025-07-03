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
    #[Route('/planning', name: 'app_planning')]
   public function index(
    FormationRepository $formationRepository,
    JourFerieRepository $jourFerieRepository,
    DateService $dateService
   
//  ): Response {
//     $formations = $formationRepository->findAllWithRelations();
//    $today = new \DateTime();

): Response {
  if (!$this->getUser()) // these two lines added that planning twig didn't display directly before login
  return $this->redirectToRoute('app_connexion');// these two lines added that planning twig didn't display directly before login

    $formations = $formationRepository->findAllWithRelations();
    $today = new \DateTime();

    // Calculate date range
    $startDate = new \DateTime('2024-01-01');
    $endDate = new \DateTime('2030-12-31');

    foreach ($formations as $f) {
        if ($f->getDateDebut() < $startDate) {
            $startDate = $f->getDateDebut();
        }
        if ($f->getDateFin() > $endDate) {
            $endDate = $f->getDateFin();
        }
    }

    // Add margin
    $startDate->modify('-1 year');
    $endDate->modify('+1 year');

    // Get all necessary data
    $months = $dateService->getMonthsBetweenDates($startDate, $endDate);
    $allWeeks = $dateService->getWeeksBetweenDates($startDate, $endDate);
    $holidays = $jourFerieRepository->findBetweenDates($startDate, $endDate);
    
    // Calculate weeks per year
    $yearlyWeeks = [];
    foreach ($allWeeks as $week) {
        $year = $week['year'];
        $yearlyWeeks[$year] = ($yearlyWeeks[$year] ?? 0) + 1;
    }

    // Calculate stagiaires per week

foreach ($allWeeks as &$week) {
    $weekStart = $week['start_date'];
    $weekEnd = $week['end_date'];

    $totalStagiaires = 0;
    $activeStagiaires = 0;

    foreach ($formations as $formation) {
        $formationStart = $formation->getDateDebut();
        $formationEnd = $formation->getDateFin() ?? $formationStart;

        if ($formationStart <= $weekEnd && $formationEnd >= $weekStart) {
            $totalStagiaires += ($formation->getnombreStagiaires() ?? 0);
            $activeStagiaires++;
        }
    }

    $week['total_stagiaires'] = $totalStagiaires;
    $week['active_stagiaires'] = $activeStagiaires;
}





//     foreach ($allWeeks as &$week) {
//         $weekStart = $week['start_date'];
//         $weekEnd = $week['end_date'];
        
//         $totalStagiaires = 0;
//         $activeStagiaires = 0;
        
//         foreach ($formations as $formation) {
//            if ($formation->getDateDebut() <= $weekEnd && $formation->getDateFin() >= $weekStart) {
//     $totalStagiaires += ($formation->getnombreStagiaires() ?? 0); // Fixed line
//     $activeStagiaires++;
// }

//         }
        
//         $week['total_stagiaires'] = $totalStagiaires;
//         $week['active_stagiaires'] = $activeStagiaires;
//     }

    // Group formations
    $groupedFormations = [];
    foreach ($formations as $f) {
        $group = $f->getGroupeRattachement() ?? 'Non groupé';
        $groupedFormations[$group][] = $f;
    }

    // Calculate day width
$totalDays = $dateService->getDaysBetween($startDate, $endDate);
$dayWidth = min(6, max(5, 1200 / $totalDays));

return $this->render('planning/index.html.twig', [
    'grouped_formations' => $groupedFormations,
    'holidays' => $holidays,
    'months' => $months,
    'yearly_weeks' => $yearlyWeeks,
    'all_weeks' => $allWeeks,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'day_width' => $dayWidth,
    'total_days' => $totalDays,
    'date_service' => $dateService
]);

}
}
















