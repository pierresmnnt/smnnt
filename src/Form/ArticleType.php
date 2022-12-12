<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleType extends AbstractType
{
    public function __construct(private SluggerInterface $slugger)
    {  
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('published', CheckboxType::class, [
                'required' => false,
                'help' => "Only you can see an unpublished article",
            ])
            ->add('topics', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->andWhere('c.type = 2');
                },
                'choice_label' => 'name',
                'help' => "Choose one or more topics",
                'attr' => ['class' => 'multiple-checkbox'],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('title', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => "Write the title of the article here",
                    'is' => "textarea-autogrow",
                    'class' => "article__title",
                ]
            ])
            ->add('kicker', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => "Write a short summary of the article here",
                    'is' => "textarea-autogrow",
                    'class' => "article__subtitle",
                ]
            ])
            ->add('heroImageUrl', UrlType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "URL of the hero image. Image should have a 3/2 aspect ratio"
                ]
            ])
            ->add('heroImageCredit', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Alt and credit of the hero image"
                ]
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => "Write the article here",
                    'is' => "textarea-autogrow"
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
                /** @var Article */
                $article = $event->getData();
                if (null !== $articleTitle = $article->getTitle()) {
                    $article->setSlug($this->slugger->slug($articleTitle)->lower());
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
