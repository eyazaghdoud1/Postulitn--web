<?php

namespace App\Form;

use App\Entity\Candidatures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class CandidaturesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('cv')
            ->add('lettre')
            ->add('date')
            ->add('etat')
            ->add('idcandidat')
            ->add('idoffre')*/
            ->add('cv', FileType::class, [
                
                'required' => false,
                'constraints' => [
                    new File([
                        
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier PDF',
                    ])
                ],
            ])
            ->add('lettre' , FileType::class, [
                
                    'required' => false,
                    'constraints' => [
                        new File([
                            
                            'mimeTypes' => [
                                'application/pdf',
                                
                            ],
                            'mimeTypesMessage' => 'Veuillez choisir un fichier PDF',
                        ])
                    ],
                ])
            ->add('save',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidatures::class,
        ]);
    }
}
