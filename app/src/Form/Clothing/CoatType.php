<?php

declare(strict_types=1);

namespace App\Form\Clothing;

use App\Entity\Clothing\Coat;
use App\Form\Product\ProductType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A coat form.
 */
final class CoatType extends ProductType
{
    // Methods :

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver the options resolver.
     */
    #[\Override()]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coat::class,
            'translation_domain' => 'product'
        ]);
    }
}
