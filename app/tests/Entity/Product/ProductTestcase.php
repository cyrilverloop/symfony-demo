<?php

declare(strict_types=1);

namespace App\Tests\Entity\Product;

use App\Entity\Product\Product;
use PHPUnit\Framework\TestCase;

/**
 * A class to test a Product entity.
 */
abstract class ProductTestcase extends TestCase
{
    // Properties :

    /**
     * @var \App\Entity\Product\Product the product.
     */
    protected Product $product;


    // Methods :

    /**
     * Test that the identifier
     * is initialised to null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        self::assertNull($this->product->id);
    }

    /**
     * Test that the name can be accessed.
     */
    public function testCanSetAndGetName(): void
    {
        $this->product->name = 'test-name';

        self::assertSame(
            'test-name',
            $this->product->name,
            'The returned name is not the one that has been defined.'
        );
    }

    /**
     * Test that the description can be accessed.
     */
    public function testCanSetAndGetDescription(): void
    {
        $this->product->description = 'test-description';

        self::assertSame(
            'test-description',
            $this->product->description,
            'The returned description is not the one that has been defined.'
        );
    }

    /**
     * Test that the description can be accessed.
     */
    public function testCanSetAndGetPrice(): void
    {
        $this->product->price = 5;

        self::assertSame(
            5,
            $this->product->price,
            'The returned price is not the one that has been defined.'
        );
    }
}
