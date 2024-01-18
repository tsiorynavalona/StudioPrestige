<?php

namespace App\Form;

use App\Entity\Photograph;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PhotographType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options['validation_groups']);

        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('about', TextareaType::class, ['label' => 'A propos'])
            ->add('image', FileType::class , ['label' => 'Photo du profil','mapped' => false,  'constraints' => [
                new NotBlank([
                    'message' => 'Choisissez une image',
                    'groups' => ['add']
                ]),
                new File([
                    // 'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpeg',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Choisissez une format photo valid',
                ])
            ]])
            ->add('save', SubmitType::class,  ['label' => 'Enregistrer'])
        ;
    }

  

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photograph::class,
            // 'novalidate' => true,
            'validation_groups' => function (FormInterface $form) {

                $va = $form->getConfig()->getOption('validation_groups');
                
                return is_array($va) ? $va : [];
            },
            
        ]);
    }
}
