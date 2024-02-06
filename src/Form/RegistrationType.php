<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            // ->add('roles')
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Vous devez au minimum entrer {{ limit }} caractères',
                    ]),
                    new NotBlank([
                        'message' => 'Ce champ ne doit pas être vide',
                    ]),
               
                ]
            ])
            ->add('password_confirm',  PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne doit pas être vide',
                    ]),
                    new Callback([
                        'callback' => [$this, 'validatePasswordConfirmation'],
                        // 'message' => 'diso'
                    ]),
                ],
            ])
            ->add('username')
            ->add('phone')
            ->add('phone2')
            ->add('address')
            ->add('submit', SubmitType::class, ['label' => 'Inscription'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function validatePasswordConfirmation($value, ExecutionContextInterface $context)
    {
        $formData = $context->getRoot()->getData();
        // var_dump($formData);
        // die();

        $userReflection = new \ReflectionObject($formData);
        $userArray = [];

        foreach ($userReflection->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $propertyValue = $property->getValue($formData);
            $userArray[$propertyName] = $propertyValue;
        }
        

        if ($value !== $userArray['password']) {
            $context->buildViolation('Mots de passe non identiques')
                ->atPath('password_confirm')
                ->addViolation();
        }
    }
}
