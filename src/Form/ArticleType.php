<?php

namespace App\Form;

use App\Entity\Article;
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
            ])
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('kicker', TextType::class, [
                'required' => true,
            ])
            ->add('heroImageUrl', UrlType::class, [
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
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
