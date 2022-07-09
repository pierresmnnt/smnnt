<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Gear;
use App\Entity\Image;
use App\Repository\CategoryRepository;
use App\Repository\GearRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('isInPortfolio', CheckboxType::class, [
                'required' => false,
                'help' => "Will this image appear in the portfolio ?",
            ])
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
                        ->andWhere('c.type = 1')
                        ->addSelect("(CASE When c.name = :name Then 0 ELSE 1 END) AS HIDDEN ord")
                        ->setParameter(':name','Best')
                        ->orderBy('ord')
                        ;
                },
                'choice_label' => 'name',
                'help' => 'Sélectionnez un album',
                'attr' => ['class' => 'multiple-checkbox'],
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('description', TextType::class, [
                'required' => false
            ])
            ->add('alt', TextType::class, [
                'required' => false
            ])
            ->add('gearCamera', EntityType::class, [
                'label' => "Camera",
                'class' => Gear::class,
                'query_builder' => function (GearRepository $gearRepository) {
                    return $gearRepository->createQueryBuilder('g')
                        ->andWhere('g.type = :value')
                        ->setParameter(':value','camera')
                        ;
                },
                'choice_label' => 'model',
                'help' => 'Sélectionnez un appareil photo'
            ])
            ->add('gearLens', EntityType::class, [
                'label' => "Lens",
                'class' => Gear::class,
                'query_builder' => function (GearRepository $gearRepository) {
                    return $gearRepository->createQueryBuilder('g')
                        ->andWhere('g.type = :value')
                        ->setParameter(':value','lens')
                        ;
                },
                'choice_label' => 'model',
                'help' => 'Sélectionnez un objectif',
                'required' => false
            ])
            ->add('exposure', TextType::class, [
                'required' => false,
                'label' => 'Exposure (second)',
                'attr' => ['placeholder' => '1/100']
            ])
            ->add('aperture', TextType::class, [
                'required' => false,
                'label' => 'Aperture (f/x)',
                'attr' => ['placeholder' => '1.8']
            ])
            ->add('iso', IntegerType::class, [
                'required' => false,
                'label' => 'ISO',
                'attr' => ['placeholder' => '100']
            ])
            ->add('focal', IntegerType::class, [
                'required' => false,
                'label' => 'Focal (mm)',
                'attr' => ['placeholder' => '50']
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
