<?php


namespace App\Form;

use App\Entity\Typeoffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class TypeoffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('save', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Typeoffre::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['description'],
                    'message' => 'Cette description existe déjà'
                ]),
            ],
        ]);
    }
}
