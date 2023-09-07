<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Identifier',
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Password',
                ],
            ])
            ->add('_remember_me', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Remember Me',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
