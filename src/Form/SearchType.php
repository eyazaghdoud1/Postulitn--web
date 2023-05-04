<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\SearchData;


class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('poste', TextType::class, [
                'required' => false,
                'label' => 'Poste',
                'attr' => ['placeholder' => 'Rechercher par poste'],
            ])
            ->add('lieu', TextType::class, [
                'required' => false,
                'label' => 'Lieu',
                'attr' => ['placeholder' => 'Rechercher par lieu'],
            ])
            ->add('dateexpiration', DateType::class, [
                'required' => false,
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Rechercher par date d\'expiration'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}