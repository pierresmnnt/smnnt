<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'help' => 'Adresse email de connexion',
                'required' => true,
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => "Email de contact",
                'help' => 'Adresse email publique',
                'required' => false,
            ])
            ->add('socialInstagram', TextType::class, [
                'label' => "Instagram",
                'required' => false
            ])
            ->add('socialGithub', TextType::class, [
                'label' => "Github",
                'required' => false
            ])
            ->add('socialLinkedin', TextType::class, [
                'label' => "Linkedin",
                'required' => false
            ])
            ->add('socialTwitter', TextType::class, [
                'label' => "Twitter",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
