<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom', 'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est obligatoire'
                ])
                ], 'attr' => [
                    'placeholder' => 'Nom',
                    // You can add other HTML attributes here if needed
                ]
            ])
            ->add('email', TextType::class, ['label' => 'Nom', 'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est obligatoire'
                    ]),
                    new Email([
                        'message' => 'Entrez un email valide'
                    ]),
                ], 'attr' => [
                    'placeholder' => 'Email',
                    // You can add other HTML attributes here if needed
                ]
            ])
            ->add('sujet', TextType::class, ['label' => 'Nom', 'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est obligatoire'
                ])
                ], 'attr' => [
                    'placeholder' => 'Sujet',
                    // You can add other HTML attributes here if needed
                ]
            ]
            )
            ->add('message', TextareaType::class, ['label' => 'Nom', 'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est obligatoire'
                ])
                ], 'attr' => [
                    'placeholder' => 'Message',
                    // You can add other HTML attributes here if needed
                ]
            ]
            )
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
