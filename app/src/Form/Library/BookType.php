<?php

declare(strict_types=1);

namespace App\Form\Library;

use App\Document\Library\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A book form.
 */
final class BookType extends AbstractType
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
            ->add('title', TextType::class, [
                'attr' => [
                    'maxLength' => 200,
                    'pattern' => '^.+$',
                    'placeholder' => 'form.title.placeholder',
                    'size' => 30,
                    'title' => 'form.title.title'
                ],
                'empty_data' => '',
                'label' => 'form.title.label'
            ])
            ->add('author', AuthorType::class)
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
    #[\Override()]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'translation_domain' => 'book'
        ]);
    }
}
