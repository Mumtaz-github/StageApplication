<?php


namespace App\Form;

use App\Entity\Formateur;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actifFormation', null, [
                'label' => 'Formation active',
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom de la formation',
                'constraints' => [new NotBlank()],
                'attr' => ['placeholder' => 'Ex: Développement Web Avancé']
            ])
            ->add('numero', TextType::class, [
                'label' => 'Numéro de formation',
                'required' => false,
                'attr' => ['placeholder' => 'Optionnel']
            ])
            ->add('etat', TextType::class, [
                'label' => 'État',
                'attr' => ['placeholder' => 'Ex: En cours de validation']
            ])
            ->add('titreProfessionnel', TextType::class, [
                'label' => 'Titre professionnel',
                'required' => false
            ])
            ->add('niveau', IntegerType::class, [
                'label' => 'Niveau',
                'attr' => ['min' => 1, 'max' => 5]
            ])
            ->add('nbStagiairesPrevisionnel', IntegerType::class, [
                'label' => 'Nombre de stagiaires',
                'attr' => ['min' => 1]
            ])
            ->add('groupeRattachement', TextType::class, [
                'label' => 'Groupe de rattachement'
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'html5' => true
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'html5' => true
            ])
        ->add('formateurs', EntityType::class, [
                'class' => Formateur::class,
                'multiple' => true,
                'expanded' => false, // Set to true for checkboxes
                'choice_label' => fn(Formateur $formateur) => $formateur->getPrenom() . ' ' . $formateur->getNom(),
                'label' => 'Formateur',
                'placeholder' => 'Sélectionner un ou plusieurs formateurs',
                'required' => false,
                'attr' => ['class' => 'select2']


            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}






