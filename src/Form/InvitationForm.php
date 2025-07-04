<?php

namespace App\Form;

use App\Entity\Invitation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvitationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email du destinataire',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une adresse email.',
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir une adresse email valide.',
                    ]),
                ],
                'attr' => ['placeholder' => 'exemple@domaine.com'],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle à attribuer',
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Gestionnaire' => 'ROLE_GESTIONNAIRE',
                    'Consultant' => 'ROLE_CONSULTATION',
                ],
                'placeholder' => 'Sélectionnez un rôle',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un rôle.',
                    ]),
                ],
                'expanded' => false, // select dropdown
                'multiple' => false, // single choice
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invitation::class,
        ]);
    }
}



























// namespace App\Form;

// use App\Entity\Invitation;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\EmailType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Validator\Constraints\Email;
// use Symfony\Component\Validator\Constraints\NotBlank;

// class InvitationForm extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('email', EmailType::class, [
//                 'label' => 'Email du destinataire',
//                 'constraints' => [
//                     new NotBlank(),
//                     new Email()
//                 ],
//                 'attr' => ['placeholder' => 'exemple@domaine.com']
//             ])
//             ->add('role', ChoiceType::class, [
//                 'label' => 'Rôle à attribuer',
//                 'choices' => [
//                     'Administrateur' => 'ROLE_ADMIN',
//                     'Gestionnaire' => 'ROLE_GESTIONNAIRE',
//                     'Consultant' => 'ROLE_CONSULTATION',
//                 ],
//                 'placeholder' => 'Sélectionnez un rôle',
//                 'constraints' => [new NotBlank()]
//             ]);
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => Invitation::class,
//         ]);
//     }
// }

