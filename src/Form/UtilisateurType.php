<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('tel')
            ->add('adresse')
            ->add('datenaissance')
            ->add('mdp', PasswordType::class, [
                /* 'type' => 
                'invalid_message' => 'Les deux champs du mot de passe doivent correspondre.',
                'required' => true,
                'second_options' => ['label' => 'Répéter le mot de passe'],*/])
            ->add('idrole', EntityType::class, [
                'class' => Role::class,
                'choice_label' => function ($role) {
                    if ($role->getDescription() === 'Recruteur') {
                        return 'Recruteur';
                    } elseif ($role->getDescription() === 'Candidat') {
                        return 'Candidat';
                    }
                },
            ])
            ->add('save', SubmitType::class);
    }

    /* public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    } */
}
