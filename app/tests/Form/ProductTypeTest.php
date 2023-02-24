<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Form\ProductType;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Tests the product form.
 */
#[
    PA\CoversClass(ProductType::class),
    PA\Group('forms'),
    PA\Group('forms_productType'),
    PA\Group('product')
]
class ProductTypeTest extends TypeTestCase
{
    // Properties :

    /**
     * @var \App\Form\ProductType the form.
     */
    private ProductType $form;


    // Methods :

    /**
     * Initialises tests.
     */
    public function setUp(): void
    {
        $this->form = new ProductType();
        parent::setUp();
    }

    /**
     * Test that the form can be built.
     */
    public function testCanBuildForm(): void
    {
        $builder = new FormBuilder('product', ProductType::class, new EventDispatcher(), $this->factory);

        $this->form->buildForm($builder, []);

        $this->assertHasNameInput($builder);
        $this->assertHasDescriptionTextarea($builder);
        $this->assertHasSubmitButton($builder);
    }

    /**
     * Asserts that the name input is present.
     * @param \Symfony\Component\Form\FormBuilder $builder the form builder.
     */
    private function assertHasNameInput(FormBuilder $builder): void
    {
        $field = $builder->get('name');
        $fieldClass = get_class($field->getType()->getInnerType());

        self::assertSame('name', $field->getName(), 'The name field must named "name".');
        self::assertSame(TextType::class, $fieldClass, 'The name field must be of type "' . TextType::class . '".');

        $options = $field->getOptions();

        self::assertArrayHasKey('attr', $options, 'The name field must have an attr option.');
        self::assertArrayHasKey('maxLength', $options['attr'], 'The name field must have a maxLength.');
        self::assertSame(50, $options['attr']['maxLength'], 'The name field maxLength must be 50.');
        self::assertArrayHasKey('pattern', $options['attr'], 'The name field must have a pattern.');
        self::assertSame('^.+$', $options['attr']['pattern'], 'The name field pattern must be "^.+$".');
        self::assertArrayHasKey('placeholder', $options['attr'], 'The name field must have a placeholder.');
        self::assertSame('form.name.placeholder', $options['attr']['placeholder'], 'The name field placeholder must be "form.name.placeholder".');
        self::assertArrayHasKey('size', $options['attr'], 'The name field must have a size.');
        self::assertSame(30, $options['attr']['size'], 'The name field size must be 30.');
        self::assertArrayHasKey('title', $options['attr'], 'The name field must have a title.');
        self::assertSame('form.name.title', $options['attr']['title'], 'The name field title must be "form.name.title".');
        self::assertArrayHasKey('empty_data', $options, 'The name field must have a empty_data option.');
        self::assertSame('', $options['empty_data'], 'The name field empty_data must be an empty string.');
        self::assertArrayHasKey('label', $options, 'The name field must have a label option.');
        self::assertSame('form.name.label', $options['label'], 'The name field label must be "form.name.label".');
    }

    /**
     * Asserts that the description textarea is present.
     * @param \Symfony\Component\Form\FormBuilder $builder the form builder.
     */
    private function assertHasDescriptionTextarea(FormBuilder $builder): void
    {
        $field = $builder->get('description');
        $fieldClass = get_class($field->getType()->getInnerType());

        self::assertSame('description', $field->getName(), 'The description field must named "description".');
        self::assertSame(TextareaType::class, $fieldClass, 'The description field must be of type "' . TextareaType::class . '".');

        $options = $field->getOptions();

        self::assertArrayHasKey('attr', $options, 'The description field must have an attr option.');
        self::assertArrayHasKey('maxLength', $options['attr'], 'The description field must have a maxLength.');
        self::assertSame(300, $options['attr']['maxLength'], 'The description field maxLength must be 300.');
        self::assertArrayHasKey('placeholder', $options['attr'], 'The description field must have a placeholder.');
        self::assertSame('form.description.placeholder', $options['attr']['placeholder'], 'The description field placeholder must be "form.description.placeholder".');
        self::assertArrayHasKey('title', $options['attr'], 'The description field must have a title.');
        self::assertSame('form.description.title', $options['attr']['title'], 'The description field title must be "form.description.title".');
        self::assertArrayHasKey('label', $options, 'The description field must have a label option.');
        self::assertSame('form.description.label', $options['label'], 'The description field label must be "form.description.label".');
        self::assertArrayHasKey('required', $options, 'The description field must have a required option.');
        self::assertFalse($options['required'], 'The description field must not be required.');
    }

    /**
     * Asserts that the submit button is present.
     * @param \Symfony\Component\Form\FormBuilder $builder the form builder.
     */
    private function assertHasSubmitButton(FormBuilder $builder): void
    {
        $field = $builder->get('submit');
        $fieldClass = get_class($field->getType()->getInnerType());

        self::assertSame('submit', $field->getName(), 'The submit field must named "submit".');
        self::assertSame(SubmitType::class, $fieldClass, 'The submit field must be of type "' . SubmitType::class . '".');

        $options = $field->getOptions();

        self::assertArrayHasKey('attr', $options, 'The submit field must have an attr option.');
        self::assertArrayHasKey('class', $options['attr'], 'The submit field must have a class.');
        self::assertSame('btn-primary', $options['attr']['class'], 'The submit field class must be "btn-primary".');
        self::assertArrayHasKey('label', $options, 'The submit field must have a label option.');
        self::assertSame('form.submit.value', $options['label'], 'The submit field label must be "form.submit.value".');
    }


    /**
     * Test that the options can be configured.
     */
    public function testCanUseEveryConfiguredOptions(): void
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'data_class',
            'translation_domain'
        ]);

        $this->form->configureOptions($resolver);

        self::assertEmpty($resolver->getMissingOptions(), 'The form must use every requirements.');
    }
}
