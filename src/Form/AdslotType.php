<?php

namespace App\Form;

use App\Entity\Adslot;
use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdslotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('advert', EntityType::class, [
                'class' => Advert::class,
                'query_builder' => function (AdvertRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('a')
                        ->andWhere('a.active = TRUE');
                },
                'choice_label' => 'name',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adslot::class,
        ]);
    }
}
