<?php

declare(strict_types=1);

namespace App\Form\Furniture;

use App\Entity\Furniture\Chair;
use App\Form\Product\ProductType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A chair form.
 */
final class ChairType extends ProductType
{
    // Methods :

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver the options resolver.
     */
    #[\Override()]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chair::class,
            'translation_domain' => 'product'
        ]);
    }
}
