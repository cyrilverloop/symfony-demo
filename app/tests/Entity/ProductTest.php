<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

/**
 * A class to test the Product entity.
 *
 * @coversDefaultClass \App\Entity\Product
 * @covers ::__construct
 */
class ProductTest extends TestCase
{
    // Properties :

    /**
     * @var \App\Entity\Product the product.
     */
    private Product $product;


    // Methods :

    /**
     * Initialises tests.
     * @return void
     */
    public function setUp(): void
    {
        $this->product = new Product('');
    }

    /**
     * Test that the name can be accessed.
     * @return void
     *
     * @covers ::getName
     * @covers ::setName
     */
    public function testCanSetAndGetName(): void
    {
        $this->product->setName('test-name');

        self::assertSame(
            'test-name',
            $this->product->getName(),
            'The returned name is not the one that has been defined.'
        );
    }

    /**
     * Test that the description can be accessed.
     * @return void
     *
     * @covers ::getDescription
     * @covers ::setDescription
     */
    public function testCanSetAndGetDescription(): void
    {
        $this->product->setDescription('test-description');

        self::assertSame(
            'test-description',
            $this->product->getDescription(),
            'The returned description is not the one that has been defined.'
        );
    }
}
