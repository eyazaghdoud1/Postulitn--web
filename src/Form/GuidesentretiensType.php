<?php

namespace App\Form;

use App\Entity\Guidesentretiens;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;

class GuidesentretiensType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


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


            ->add('specialite', TextType::class, [
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



            ->add('support')
            /*, TextType::class, [
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
*/


            //->add('note')
            //->add('nombrenotes')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guidesentretiens::class,
        ]);
    }
}