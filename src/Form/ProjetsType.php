<?php

namespace App\Form;

use App\Entity\Projets;
use App\Entity\Secteurs;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime as DateTimeConstraint;


class ProjetsType extends AbstractType
{


    private $entityManager;

    // Inject the EntityManagerInterface as a dependency in the constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $secteurs = $this->entityManager->getRepository(Secteurs::class)->findAll();
        $secteurChoices = [];
        foreach ($secteurs as $secteur) {
            // Populate the choices array with the secteur names
            $secteurChoices[$secteur->getDescription()] = $secteur->getIdSecteur();
        }

        $builder
            ->add('nom')
            ->add('theme')
            ->add('description')
            ->add('duree')
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
                'constraints' => [
                    new GreaterThan([
                        'value' => 'today',
                        'message' => 'La date de début doit être strictement supérieure à la date actuelle.',
                    ]),
    
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'), // Date minimale possible est la date actuelle
                ],
            ])
            ->add('datefin', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan([
                        'propertyPath' => 'parent.all[datedebut].data', // Référence à la date de début
                        'message' => 'La date de fin doit être strictement supérieure à la date de début.',
                    ]),
                ],
                'attr' => [
                    'min' => (new \DateTime('+1 day'))->format('Y-m-d'), // Date minimale possible est la date de début + 1 jour
                ],
            ])
            ->add('idsecteur', EntityType::class, [
                'class' => Secteurs::class,
                'choice_label' => 'description',
            ])
            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
