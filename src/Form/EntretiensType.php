<?php

namespace App\Form;

use App\Entity\Entretiens;
use App\Entity\Guidesentretiens;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

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
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'En présentiel' => "Présentiel",
                    'Par téléphone' => "Téléphone",

                ], 'attr' => array(
                    'label' => 'Type de l\'entretien'
                )
            ])
            ->add('date', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',


            ])
           
            ->add('heure', TimeType::class, [
                'input' => 'string',
                'widget' => 'choice',
                'hours' => range(0, 23),
                'minutes' => range(0, 59, 5) // limit minutes to multiples of 5]
            ])
            ->add('lieu')
            ->add(
                'idguide',
                EntityType::class,
                [
                    'class' => Guidesentretiens::class,
                    'choice_label' => 'specialite',
                    'multiple' => false,
                    'expanded' => false,

                ]
            )
            ->add('save', SubmitType::class);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entretiens::class,
        ]);
    }
}
