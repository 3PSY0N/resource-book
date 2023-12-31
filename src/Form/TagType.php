<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\CssColor;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 25]),
                ],
                'attr'        => [
                    'placeholder' => 'Tag name',
                ],
            ])
            ->add('fgColor', TextType::class, [
                'label'       => 'Foreground color',
                'required'    => false,
                'attr'        => [
                    'data-coloris' => '',
                    'placeholder'  => '#000000'
                ],
                'constraints' => [
                    new CssColor([
                        CssColor::HEX_SHORT,
                        CssColor::HEX_LONG,
                        CssColor::HEX_LONG_WITH_ALPHA,
                        CssColor::RGB,
                        CssColor::RGBA,
                        CssColor::HSL,
                        CssColor::HSLA,
                    ]),
                ],
            ])
            ->add('bgColor', TextType::class, [
                'label'       => 'Background color',
                'required'    => false,
                'attr'        => [
                    'data-coloris' => '',
                    'placeholder'  => '#000000'
                ],
                'constraints' => [
                    new CssColor([
                        CssColor::HEX_SHORT,
                        CssColor::HEX_LONG,
                        CssColor::HEX_LONG_WITH_ALPHA,
                        CssColor::RGB,
                        CssColor::RGBA,
                        CssColor::HSL,
                        CssColor::HSLA,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
