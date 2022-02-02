<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * A product form.
 */
class ProductType extends AbstractType
{
    // Methods :

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder the form builder.
     * @param array $options the options.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'maxLength' => 50,
                    'pattern' => '^.+$',
                    'placeholder' => 'form.name.placeholder',
                    'size' => 30,
                    'title' => 'form.name.title'
                ],
                'empty_data' => '',
                'label' => 'form.name.label'
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'maxLength' => 300,
                    'placeholder' => 'form.description.placeholder',
                    'title' => 'form.description.title'
                ],
                'label' => 'form.description.label',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-primary'
                ],
                'label' => 'form.submit.value'
            ])
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver the options resolver.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'translation_domain' => 'product'
        ]);
    }
}
