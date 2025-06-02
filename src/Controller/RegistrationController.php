<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Entity\Invitation;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register/invite/{token}', name: 'app_register_by_invite')]
    public function registerByInvite(
        string $token,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $invitation = $em->getRepository(Invitation::class)->findOneBy(['token' => $token]);

        if (!$invitation || $invitation->isUsed() || $invitation->getExpiresAt() < new \DateTime()) {
            throw $this->createNotFoundException('Lien invalide ou expiré.');
        }

        $utilisateur = new Utilisateurs();
        $utilisateur->setEmail($invitation->getEmail());
        $utilisateur->setRole('$invitation->getRole'); 
        $utilisateur->setDateInvitation(new \DateTime()); // Register date

        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword(
                $passwordHasher->hashPassword($utilisateur, $form->get('plainPassword')->getData())
            );

            $em->persist($utilisateur);
            $invitation->setUsed(true);
            $em->flush();

            $this->addFlash('success', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/invite.html.twig', [
            'registrationForm' => $form,
            'email' => $invitation->getEmail()
        ]);
    }
}


