<?php

namespace App\Form;

use App\Entity\Entretiens;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EntretiensType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('type')
            ->add('date')
            ->add('heure')
            ->add('lieu')
            ->add('idcandidature')
            ->add('idguide')
        ;*/
        ->add('type')
            ->add('date', DateTimeType::class , [
                // renders it as a single text box
                'widget' => 'single_text',
            ])
            ->add('heure')
            ->add('lieu')
            ->add('idguide', EntityType::class , [
                'class' => Guidesentretiens::class,
                'choice_label' => 'specialite',
                'multiple' => false,
                'expanded' => false,
            
                'attr' => array(
                    'label' => 'Guide d\'entretien'
                )]
            )
            ->add('save',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entretiens::class,
        ]);
    }
}
