<?php

declare(strict_types=1);

namespace App\Tests\Form\Clothing;

use App\Form\Clothing\CoatType;
use App\Tests\Form\Product\ProductTypeTestcase;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;

/**
 * A class to test the coat form.
 */
#[
    PA\CoversClass(CoatType::class),
    PA\Group('forms'),
    PA\Group('forms_coatType'),
    PA\Group('coat')
]
final class CoatTypeTest extends ProductTypeTestcase
{
    // Methods :

    /**
     * Initialises tests.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new CoatType();
    }

    /**
     * Test that the form can be built.
     */
    public function testCanBuildForm(): void
    {
        $builder = new FormBuilder('product', CoatType::class, new EventDispatcher(), $this->factory);

        $this->form->buildForm($builder, []);

        $this->assertHasNameInput($builder);
        $this->assertHasDescriptionTextarea($builder);
        $this->assertHasPriceInput($builder);
        $this->assertHasSubmitButton($builder);
    }
}
