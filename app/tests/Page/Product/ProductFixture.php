<?php

declare(strict_types=1);

namespace App\Tests\Page\Product;

use App\Entity\Product;

/**
 * A trait to add a product fixture.
 */
trait ProductFixture
{
    // Methods :

    /**
     * Adds a product fixture
     * to the database.
     */
    public function addProductFixture(): void
    {
        $product = new Product('test-name', 'test-description');

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }
}
