<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryType extends AbstractType
{
    public function __construct(private SluggerInterface $slugger)
    {  
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'choices' => Category::TYPE,
                'attr' => ['class' => 'multiple-checkbox'],
                'expanded' => true,
                'required' => true
            ])
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
                /** @var Category */
                $category = $event->getData();
                if (null !== $categoryName = $category->getName()) {
                    $category->setSlug($this->slugger->slug($categoryName)->lower());
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
