<?php

namespace App\Form;

use App\Entity\Candidatures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
                /*'attr' => array(
                    'placeholder' => 'Veuillez indiquer votre cv'
                )*/
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the details
               // 'label' => 'Brochure (PDF file)',
                'required' => false,
            ])
            ->add('lettre' , FileType::class, [
                /*'attr' => array(
                    'placeholder' => 'Veuillez indiquer votre lettre de motivation') */
                    'required' => false,
                    ]
                )
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
