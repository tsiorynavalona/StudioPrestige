<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Entrez votre nouveau mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => '',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Vous devez au minimum entrer {{ limit }} caractères',
                    ]),
    
                ],
            ])
            ->add('password_confirm',  PasswordType::class, [
                // 'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please confirm your password',
                    ]),
                    new Callback([
                        'callback' => [$this, 'validatePasswordConfirmation'],
                        // 'message' => 'diso'
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    public function validatePasswordConfirmation($value, ExecutionContextInterface $context)
    {
        $formData = $context->getRoot()->getData();
        // var_dump($formData);
        // die();
        // dd($formData);

        // $userReflection = new \ReflectionObject($formData);
        // $userArray = [];

        // foreach ($userReflection->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
        //     $property->setAccessible(true);
        //     $propertyName = $property->getName();
        //     $propertyValue = $property->getValue($formData);
        //     $userArray[$propertyName] = $propertyValue;
        // }
        

        if ($value !== $formData['password']) {
            $context->buildViolation('Mots de passe non identiques')
                ->atPath('password_confirm')
                ->addViolation();
        }
    }
}
