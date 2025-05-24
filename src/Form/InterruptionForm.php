<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Interruption;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterruptionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebutInt')
            ->add('dateFinInt')
            ->add('formation', EntityType::class, [
                    'class' => Formation::class,
                    'choice_label' => 'nom', // or create a getDisplayName() in Formation
                    'placeholder' => 'Choisir une formation'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interruption::class,
        ]);
    }
}
