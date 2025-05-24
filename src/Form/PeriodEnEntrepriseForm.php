<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\PeriodEnEntreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PeriodEnEntrepriseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une formation'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PeriodEnEntreprise::class,
        ]);
    }
}
