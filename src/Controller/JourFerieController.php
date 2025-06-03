<?php

namespace App\Controller;

use App\Entity\JourFerie;
use App\Form\JourFerieForm;
use App\Message\SyncHolidaysMessage;
use App\Repository\JourFerieRepository;
use App\Service\JourFerieApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/jour/ferie')]
final class JourFerieController extends AbstractController
{
    #[Route(name: 'app_jour_ferie_index', methods: ['GET'])]
    public function index(JourFerieRepository $jourFerieRepository, Request $request): Response
    {
        $zone = $request->query->get('zone');
        $annee = $request->query->get('annee');

        $criteria = [];
        if ($zone) $criteria['zone'] = $zone;
        if ($annee) $criteria['annee'] = $annee;

        $jourFeries = $jourFerieRepository->findBy($criteria, ['date' => 'ASC']);

        if ($request->getPreferredFormat() === 'json') {
            return $this->json([
                'jour_feries' => array_map(fn(JourFerie $j) => [
                    'id' => $j->getId(),
                    'date' => $j->getDate()->format('Y-m-d'),
                    'nom' => $j->getNom(),
                    'zone' => $j->getZone(),
                    'annee' => $j->getAnnee(),
                ], $jourFeries)
            ]);
        }

        return $this->render('jour_ferie/index.html.twig', [
            'jour_feries' => $jourFeries,
            'zones' => $jourFerieRepository->findDistinctZones(),
            'annees' => $jourFerieRepository->findDistinctAnnees(),
        ]);
    }

    #[Route('/new', name: 'app_jour_ferie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        $jourFerie = new JourFerie();
        $form = $this->createForm(JourFerieForm::class, $jourFerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($em->getRepository(JourFerie::class)->findOneBy([
                'date' => $jourFerie->getDate(),
                'zone' => $jourFerie->getZone(),
                'annee' => $jourFerie->getAnnee(),
            ])) {
                $this->addFlash('error', 'Un jour férié existe déjà pour cette date, zone et année.');
                return $this->redirectToRoute('app_jour_ferie_new');
            }

            $em->persist($jourFerie);
            $em->flush();
            $this->addFlash('success', 'Jour férié créé avec succès.');
            return $this->redirectToRoute('app_jour_ferie_index');
        }

        return $this->render('jour_ferie/new.html.twig', [
            'jour_ferie' => $jourFerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jour_ferie_show', methods: ['GET'])]
    public function show(JourFerie $jourFerie): Response
    {
        return $this->render('jour_ferie/show.html.twig', ['jour_ferie' => $jourFerie]);
    }

    #[Route('/{id}/edit', name: 'app_jour_ferie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JourFerie $jourFerie, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(JourFerieForm::class, $jourFerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existing = $em->getRepository(JourFerie::class)->findOneBy([
                'date' => $jourFerie->getDate(),
                'zone' => $jourFerie->getZone(),
                'annee' => $jourFerie->getAnnee(),
            ]);

            if ($existing && $existing->getId() !== $jourFerie->getId()) {
                $this->addFlash('error', 'Un jour férié existe déjà pour cette date, zone et année.');
                return $this->redirectToRoute('app_jour_ferie_edit', ['id' => $jourFerie->getId()]);
            }

            $em->flush();
            $this->addFlash('success', 'Jour férié mis à jour avec succès.');
            return $this->redirectToRoute('app_jour_ferie_index');
        }

        return $this->render('jour_ferie/edit.html.twig', [
            'jour_ferie' => $jourFerie,
            'form' => $form,
        ]);
    }

    #[Route('/sync-api', name: 'app_jour_ferie_sync_api', methods: ['POST'])]
    public function syncFromApi(Request $request, MessageBusInterface $messageBus): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $zone = $request->request->get('zone', 'metropole');
        $year = $request->request->get('year', date('Y'));

        $messageBus->dispatch(new SyncHolidaysMessage($zone, $year));
        $this->addFlash('success', 'Synchronisation des jours fériés démarrée en arrière-plan');
        
        return $this->redirectToRoute('app_jour_ferie_index');
    }

    #[Route('/{id}', name: 'app_jour_ferie_delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, JourFerie $jourFerie, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jourFerie->getId(), $request->getPayload()->get('_token'))) {
            $em->remove($jourFerie);
            $em->flush();
            $this->addFlash('success', 'Jour férié supprimé avec succès.');
        }

        return $request->isXmlHttpRequest() 
            ? new JsonResponse(['status' => 'success']) 
            : $this->redirectToRoute('app_jour_ferie_index');
    }

    #[Route('/api/list', name: 'app_jour_ferie_api', methods: ['GET'])]
    public function apiList(JourFerieRepository $repo): JsonResponse
    {
        return $this->json(array_map(fn(JourFerie $j) => [
            'id' => $j->getId(),
            'date' => $j->getDate()->format('Y-m-d'),
            'nom' => $j->getNom(),
            'zone' => $j->getZone(),
            'annee' => $j->getAnnee(),
        ], $repo->findAll()));
    }
}