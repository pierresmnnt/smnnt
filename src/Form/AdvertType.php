<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('cta', TextType::class, [
                'required' => true,
            ])
            ->add('landingPage', TextType::class, [
                'required' => true,
            ])
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'required' => true,
            ])
            ->add('advertiser', TextType::class, [
                'required' => true,
            ])
            ->add('logo', UrlType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
