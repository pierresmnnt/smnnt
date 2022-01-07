<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Image;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Remove Image',
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add('albums', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->andWhere('c.type = 1');
                },
                'choice_label' => 'name',
                'help' => 'Sélectionnez un album',
                'attr' => ['class' => 'multiple-checkbox'],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('description', TextType::class, [
                'required' => false
            ])
            ->add('alt', TextType::class, [
                'required' => true
            ])
            ->add('camera', TextType::class, [
                'required' => false
            ])
            ->add('lens', TextType::class, [
                'required' => false
            ])
            ->add('exposure', TextType::class, [
                'required' => false
            ])
            ->add('aperture', TextType::class, [
                'required' => false
            ])
            ->add('iso', IntegerType::class, [
                'required' => false
            ])
            ->add('focal', IntegerType::class, [
                'required' => false
            ])
            ->add('date', DateType::class, [
                'required' => false,
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
