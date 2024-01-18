<?php

namespace App\Form;

use App\Entity\Photos;
use App\Entity\Photograph;
use App\Entity\CategoriesPhotos;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PhotoType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $em = new EntityManagerInterface;
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'] )
            ->add('upload', FileType::class, ['label' => 'Image','required' => true,'mapped' => false, 'constraints' => [
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
                    'mimeTypesMessage' => 'Choisissez une format photo valide',
                    'groups' => ['add','edit']
                ])
            ]])
            ->add('descriptions', TextareaType::class)
            ->add('alt')
            // ->add('url')
            ->add('categoriesPhotos', EntityType::class, 
                    ['class' => CategoriesPhotos::class, 'choice_label' => 'label',
                    'choices' => $this->em->getRepository(CategoriesPhotos::class)->findAll(), 
                    
                    'multiple' => true, 'expanded' => false])
            ->add('client')
            ->add('id_photograph', EntityType::class, [ 'label' => 'Photographe','class' => Photograph::class, 'choice_label' => 'name', 'placeholder' => '', 'multiple' => false, 'expanded' => false])
            ->add('submit', SubmitType::class, ['label' => 'Ajouter'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photos::class,
            'validation_groups' => ['add','edit'],
        ]);
    }
}
