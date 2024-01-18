<?php

namespace App\Form;

use App\Entity\Photograph;
use App\Entity\CategoriesPhotos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterPhotosType extends AbstractType
{
    public function __construct(private EntityManagerInterface $em) {
        // $this->var = $var;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', EntityType::class, 
                [
                    'class' => CategoriesPhotos::class,
                    'choice_label' => 'label',
                    'placeholder' => 'Catégorie(s)',
                    'attr' => ['min-width' => '200px'],
                    'choices' => $this->em->getRepository(CategoriesPhotos::class)->findAll(), 
                    'multiple' => true, 'expanded' => false,
                    'required'   => false,
                    'empty_data' => null,
                    'invalid_message' => '',
                ])
            ->add('photograph',  EntityType::class, 
                [
                    'class' => Photograph::class, 
                    'choice_label' => 'name',
                    'placeholder' => 'Photographe',
                    // 'placeholder' => '', 
                    'multiple' => false,
                    'expanded' => false, 
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'empty_data' => null,
                
                ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
