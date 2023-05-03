<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlankValidator;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ResetPwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            /*->add('mdp', PasswordType::class) , [
             
            'second_options' => ['label' => 'Répéter le mot de passe'],]*/
            ->add('mdp', PasswordType::class, [
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),

                ],
            ])
            ->add('confirmMdp', PasswordType::class, [
                'label' => 'Répéter mdp',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez confirmer votre mot de passe',
                    ]),

                    new Callback([
                        'callback' => function ($confirmMdp, ExecutionContextInterface $context) {
                            $form = $context->getRoot();
                            $mdp = $form->get('mdp')->getData();

                            if ($mdp !== $confirmMdp) {
                                $context->buildViolation('Les mots de passe ne correspondent pas')
                                    ->atPath('confirmMdp')
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
            ])

            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
