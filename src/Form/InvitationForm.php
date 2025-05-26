<?php

namespace App\Form;

use App\Entity\Invitation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvitationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $builder
        //     ->add('email')
        //     ->add('token')
        //     ->add('expiresAT')
        //     ->add('used')
        // ;

  $builder
            ->add('email')
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle à attribuer',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Gestionnaire' => 'ROLE_GESTIONNAIRE',
                    'Consultation' => 'ROLE_CONSULTATION',
                ],
                'placeholder' => 'Sélectionnez un rôle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invitation::class,
        ]);
    }
}
