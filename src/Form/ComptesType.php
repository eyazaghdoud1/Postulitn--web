<?php

namespace App\Form;

use App\Entity\Comptes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;

class Comptes1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo')
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




            ->add('datediplome')
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
