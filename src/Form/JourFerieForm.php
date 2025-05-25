<?php

namespace App\Form;

use App\Entity\JourFerie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourFerieForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
            ->add('annee')
            ->add('zone')
            ->add('nom');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JourFerie::class,
        ]);
    }
}
















// namespace App\Form;

// use App\Entity\JourFerie;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class JourFerieForm extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('date')
//             ->add('dateJourFerie')
//             ->add('dateDebutJourFerie')
//             ->add('dateFinJourFerie')
//             ->add('nom')
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => JourFerie::class,
//         ]);
//     }
// }
