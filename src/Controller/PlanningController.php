<?php
// src/Controller/PlanningController.php

namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DateLibrary\DateService;
use App\Repository\JourFerieRepository;
use DateTime;

class PlanningController extends AbstractController
{
    #[Route('/', name: 'app_planning')]
    public function index(
        FormationRepository $formationRepository,
        JourFerieRepository $jourFerieRepository,
        DateService $dateService
    ): Response {
        $formations = $formationRepository->findAll();

        $year = (int)(new \DateTime())->format('Y');
        $zone = 'metropole';

        $joursFeries = $jourFerieRepository->findBy([
            'annee' => $year,
            'zone' => $zone,
        ]);

        $holidayDates = array_map(
            fn($j) => $j->getDate()->format('Y-m-d'),
            $joursFeries
        );

        return $this->render('planning/index.html.twig', [
            'formations' => $formations,
            'joursFeries' => $holidayDates,
            'dateService' => $dateService,
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
