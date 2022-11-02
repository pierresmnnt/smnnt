<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class SearchType extends AbstractType
{
    public function __construct(private SluggerInterface $slugger)
    {  
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formOptions = [
            'class' => Category::class,
            'query_builder' => function (CategoryRepository $categoryRepository) use ($options) {
                $query = $categoryRepository->createQueryBuilder('c');

                switch ($options['controller']) {
                    case 'article':
                        $query->andWhere('c.type = 2');
                        break;
                    case 'portfolio':
                        $query->andWhere('c.type = 1')
                            ->addSelect("(CASE When c.name = :name Then 0 ELSE 1 END) AS HIDDEN ord")
                            ->setParameter(':name','Best')
                            ->orderBy('ord');
                        break;
                }
                
                return $query;
            },
            'choice_label' => function (Category $category) {
                return $category->getName();
            },
            'choice_value' => function (?Category $category) {
                return $category ? $this->slugger->slug($category->getName())->lower() : '';
            },
            'label' => false,
            'attr' => ['class' => 'multiple-checkbox'],
            'multiple' => false,
            'expanded' => true,
            'required' => false,
        ];

        switch ($options['controller']) {
            case 'article':
                $formOptions['placeholder'] = 'Tous les articles';
                break;
            case 'portfolio':
                $formOptions['placeholder'] = 'Toutes les photos';
                break;
        }

        $builder->add('category', EntityType::class, $formOptions);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'controller' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
