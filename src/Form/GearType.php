<?php

namespace App\Form;

use App\Entity\Gear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GearType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class, [
            'placeholder' => 'Choose an option',
            'choices' => Gear::TYPE,
            'attr' => ['class' => 'multiple-checkbox'],
            'expanded' => true,
            'required' => true
        ])
            ->add('model', TextType::class, [
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gear::class,
        ]);
    }
}
