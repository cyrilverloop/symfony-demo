<?php

declare(strict_types=1);

namespace App\Form\Library;

use App\Document\Library\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An author form.
 */
final class AuthorType extends AbstractType
{
    // Methods :

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder the form builder.
     * @param array $options the options.
     */
    #[\Override()]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'maxLength' => 200,
                    'pattern' => '^.+$',
                    'placeholder' => 'form.firstname.placeholder',
                    'size' => 30,
                    'title' => 'form.firstname.title'
                ],
                'empty_data' => '',
                'label' => 'form.firstname.label'
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'maxLength' => 200,
                    'pattern' => '^.+$',
                    'placeholder' => 'form.lastname.placeholder',
                    'size' => 30,
                    'title' => 'form.lastname.title'
                ],
                'empty_data' => '',
                'label' => 'form.lastname.label'
            ])
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver the options resolver.
     */
    #[\Override()]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
            'translation_domain' => 'author'
        ]);
    }
}
