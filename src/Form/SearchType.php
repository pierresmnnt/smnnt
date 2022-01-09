<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->andWhere('c.type = 1')
                        ->addSelect("(CASE When c.name = :name Then 0 ELSE 1 END) AS HIDDEN ord")
                        ->setParameter(':name','Best')
                        ->orderBy('ord')
                        ;
                },
                'choice_label' => function ($category) {
                    return $category->getIcon() . " " . $category->getName();
                },
                'label' => false,
                'attr' => ['class' => 'multiple-checkbox'],
                'multiple' => true,
                'expanded' => true
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
