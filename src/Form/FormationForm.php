<?php

namespace App\Form;

use App\Entity\Formateur;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actifFormation')
            ->add('nom')
            ->add('numero')
            ->add('etat')
            ->add('titreProfessionnel')
            ->add('niveau')
            ->add('nbStagiairesPrevisionnel')
            ->add('groupeRattachement')
            ->add('dateDebut', DateType::class, ['widget' => 'single_text'])
            ->add('dateFin', DateType::class, ['widget' => 'single_text'])
            ->add('formateur', EntityType::class, [
    'class' => Formateur::class,
    'choice_label' => function (Formateur $formateur) {
        return $formateur->getNom() . ' ' . $formateur->getPrenom(); // shows the instructor's full name
    },
    'placeholder' => 'Sélectionner un formateur',


            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
