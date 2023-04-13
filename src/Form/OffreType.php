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



class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poste')
            ->add('description')
            ->add('lieu')
            ->add('entreprise')
            ->add('specialite')
            ->add('dateexpiration')
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
