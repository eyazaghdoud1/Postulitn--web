<?php

namespace App\Form;

use App\Entity\Comptes;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class Comptes1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photo')
           /* ->add('photo', FileType::class, [
                
                'required' => false,
                'constraints' => [
                    new File([
                        
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une image',
                    ])
                ],
            ])*/
            ->add('diplome', TextType::class, [
                'attr' => [
                    'pattern' => '[A-Za-z]+',
                    'title' => 'Le champ ne doit pas contenir de chiffres'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[^0-9]*$/',
                        'message' => 'Le champ ne doit pas contenir de chiffres'
                    ])
                ]
            ])




            ->add('datediplome', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',


            ])

            ->add('entreprise', TextType::class, [
                'attr' => [
                    'pattern' => '[A-Za-z]+',
                    'title' => 'Le champ ne doit pas contenir de chiffres'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[^0-9]*$/',
                        'message' => 'Le champ ne doit pas contenir de chiffres'
                    ])
                ]
            ])



            ->add('experience')
            ->add('domaine', TextType::class, [
                'attr' => [
                    'pattern' => '[A-Za-z]+',
                    'title' => 'Le champ ne doit pas contenir de chiffres'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[^0-9]*$/',
                        'message' => 'Le champ ne doit pas contenir de chiffres'
                    ])
                ]
            ])


            ->add('poste', TextType::class, [
                'attr' => [
                    'pattern' => '[A-Za-z]+',
                    'title' => 'Le champ ne doit pas contenir de chiffres'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[^0-9]*$/',
                        'message' => 'Le champ ne doit pas contenir de chiffres'
                    ])
                ]
            ])

            ->add('save', SubmitType::class)

           // ->add('idutilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comptes::class,
        ]);
    }
}
