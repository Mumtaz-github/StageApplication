<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite', ChoiceType::class, [
              'label' => 'Civilité',
              'choices' => [
                'Monsieur' => 'monsieur',
                'Madame' => 'madame',
              ],
             'expanded' => true,
             'multiple' => false,
             'constraints' => [
              new NotBlank(['message' => 'Veuillez sélectionner une civilité']),
             ],

            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'disabled' => true, // prevent editing
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est obligatoire']),
                    new Email(['message' => 'Veuillez entrer un email valide']),
                ],
                'attr' => ['autocomplete' => 'email'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire']),
                ],
            ])
            ->add('codepostal', TextType::class, [
                'label' => 'Code Postal',
                'constraints' => [
                    new NotBlank(['message' => 'Le code postal est obligatoire']),
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => 'Le code postal doit contenir exactement 5 caractères',
                    ]),
                ],
                'attr' => [
                    'pattern' => '\d{5}',
                    'title' => '5 chiffres requis (ex: 75000)',
                ],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank(['message' => 'La ville est obligatoire']),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'placeholder' => 'Minimum 8 caractères',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation',
                    'attr' => [
                        'placeholder' => 'Répétez le mot de passe',
                    ],
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est obligatoire']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}




