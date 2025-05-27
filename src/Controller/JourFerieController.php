<?php

namespace App\Controller;

use App\Entity\JourFerie;
use App\Form\JourFerieForm;
use App\Repository\JourFerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/jour/ferie')]
final class JourFerieController extends AbstractController
{
    #[Route(name: 'app_jour_ferie_index', methods: ['GET'])]
    public function index(JourFerieRepository $jourFerieRepository, Request $request): Response
    {
        $zone = $request->query->get('zone');
        $annee = $request->query->get('annee');

        $criteria = [];

        if ($zone) {
            $criteria['zone'] = $zone;
        }

        if ($annee) {
            $criteria['annee'] = $annee;
        }

        $jourFeries = $jourFerieRepository->findBy($criteria, ['date' => 'ASC']);

        return $this->render('jour_ferie/index.html.twig', [
            'jour_feries' => $jourFeries,
        ]);
    }

    #[Route('/new', name: 'app_jour_ferie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jourFerie = new JourFerie();
        $form = $this->createForm(JourFerieForm::class, $jourFerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jourFerie);
            $entityManager->flush();

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
        return $this->render('jour_ferie/show.html.twig', [
            'jour_ferie' => $jourFerie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jour_ferie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JourFerie $jourFerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JourFerieForm::class, $jourFerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_jour_ferie_index');
        }

        return $this->render('jour_ferie/edit.html.twig', [
            'jour_ferie' => $jourFerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jour_ferie_delete', methods: ['POST'])]
    public function delete(Request $request, JourFerie $jourFerie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $jourFerie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($jourFerie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_jour_ferie_index');
    }
}

