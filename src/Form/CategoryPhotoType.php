<?php

namespace App\Form;

use App\Entity\CategoriesPhotos;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryPhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, ['label' => 'Libellé'])
            ->add('descriptions', TextareaType::class, ['label' => 'Descriptions'])
            ->add('image', FileType::class, ['label' => 'Image', 'mapped' => false,  'constraints' => [
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
                    'groups' => ['add', 'edit']
                ])
            ]])
            ->add('tarif', NumberType::class, ['label' => 'Tarif par heure', 'constraints' => [
                new Type(['type' => 'float', 'message' => 'Ce champ doit être une chiffre présentant un tarif (ex : 24, 25.5, ...)', 'groups' => ['add', 'edit']]),
            ]])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoriesPhotos::class,
            // 'novalidate' => true,
            'validation_groups' => ['add', 'edit'],
        ]);
    }
}
