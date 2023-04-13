<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Typeoffre;
use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;




class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poste', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 10,
                        'minMessage' => 'Le champ poste doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le champ poste ne peut pas contenir plus de {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]*$/',
                        'message' => 'Le champ poste ne doit contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('description')
            ->add('lieu')
            ->add('entreprise')
            ->add('specialite')
            ->add('dateexpiration', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('idtype', EntityType::class, [
                'class' => Typeoffre::class,
                'choice_label' => 'description',
            ])
            ->add('idrecruteur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'id'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
