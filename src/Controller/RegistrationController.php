<?php
use App\Entity\User;
use App\Entity\Invitation;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    $user = new User();
    $user->setEmail($invitation->getEmail());

    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user->setPassword(
            $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
        );

        $em->persist($user);

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

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// final class RegistrationController extends AbstractController
// {
//     #[Route('/registration', name: 'app_registration')]
//     public function index(): Response
//     {
//         return $this->render('registration/index.html.twig', [
//             'controller_name' => 'RegistrationController',
//         ]);
//     }
// }
