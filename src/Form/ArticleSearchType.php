<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->andWhere('c.type = 2')
                        ;
                },
                'choice_label' => function (Category $category) {
                    return $category->getName();
                },
                'choice_value' => function (?Category $category) {
                    return $category ? $category->getName() : '';
                },
                'label' => false,
                'attr' => ['class' => 'multiple-checkbox'],
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => 'Tous les articles',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
