<?php

declare(strict_types=1);

namespace App\Tests\Form\Furniture;

use App\Form\Furniture\ChairType;
use App\Tests\Form\Product\ProductTypeTestcase;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;

/**
 * A class to test the chair form.
 */
#[
    PA\CoversClass(ChairType::class),
    PA\Group('forms'),
    PA\Group('forms_chairType'),
    PA\Group('chair')
]
final class ChairTypeTest extends ProductTypeTestcase
{
    // Methods :

    /**
     * Initialises tests.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new ChairType();
    }

    /**
     * Test that the form can be built.
     */
    public function testCanBuildForm(): void
    {
        $builder = new FormBuilder('product', ChairType::class, new EventDispatcher(), $this->factory);

        $this->form->buildForm($builder, []);

        $this->assertHasNameInput($builder);
        $this->assertHasDescriptionTextarea($builder);
        $this->assertHasPriceInput($builder);
        $this->assertHasSubmitButton($builder);
    }
}
