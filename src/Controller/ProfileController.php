<?php
// src/Controller/ProfileController.php
namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\ProfileEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('profile/index.html.twig');
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    // src/Controller/ProfileController.php
public function edit(
    Request $request,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher
): Response {
    $user = $this->getUser();
    $form = $this->createForm(ProfileEditFormType::class, $user);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Handle password change if new password provided
        if ($newPassword = $form->get('newPassword')->getData()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $newPassword)
            );
        }

        $em->flush();
        $this->addFlash('success', 'Profil mis à jour!');
        return $this->redirectToRoute('app_profile');
    }

    return $this->render('profile/edit.html.twig', [
        'editForm' => $form->createView()
    ]);
}
}