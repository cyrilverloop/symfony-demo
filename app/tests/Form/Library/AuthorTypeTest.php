<?php

declare(strict_types=1);

namespace App\Tests\Form\Library;

use App\Document\Library\Author;
use App\Form\Library\AuthorType;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A class to test the author form.
 */
#[
    PA\CoversClass(AuthorType::class),
    PA\UsesClass(Author::class),
    PA\Group('forms'),
    PA\Group('forms_authorType'),
    PA\Group('author')
]
final class AuthorTypeTest extends TypeTestCase
{
    // Methods :

    /**
     * Test that the form can be built.
     */
    public function testCanBuildForm(): void
    {
        $builder = new FormBuilder('author', AuthorType::class, new EventDispatcher(), $this->factory);

        $form = new AuthorType();
        $form->buildForm($builder, []);

        $this->assertHasFirstnameInput($builder);
        $this->assertHasLastnameInput($builder);
    }

    /**
     * Asserts that the firstname input is present.
     * @param \Symfony\Component\Form\FormBuilder $builder the form builder.
     */
    private function assertHasFirstnameInput(FormBuilder $builder): void
    {
        $field = $builder->get('firstname');
        $fieldClass = get_class($field->getType()->getInnerType());

        self::assertSame('firstname', $field->getName(), 'The firstname field must named "firstname".');
        self::assertSame(TextType::class, $fieldClass, 'The firstname field must be of type "' . TextType::class . '".');

        $options = $field->getOptions();

        self::assertArrayHasKey('attr', $options, 'The firstname field must have an attr option.');
        self::assertArrayHasKey('maxLength', $options['attr'], 'firstname field must have a maxLength.');
        self::assertSame(200, $options['attr']['maxLength'], 'The firstname field maxLength must be 200.');
        self::assertArrayHasKey('pattern', $options['attr'], 'The firstname field must have a pattern.');
        self::assertSame('^.+$', $options['attr']['pattern'], 'The firstname field pattern must be "^.+$".');
        self::assertArrayHasKey('placeholder', $options['attr'], 'The firstname field must have a placeholder.');
        self::assertSame('form.firstname.placeholder', $options['attr']['placeholder'], 'The firstname field placeholder must be "form.firstname.placeholder".');
        self::assertArrayHasKey('size', $options['attr'], 'The firstname field must have a size.');
        self::assertSame(30, $options['attr']['size'], 'The firstname field size must be 30.');
        self::assertArrayHasKey('title', $options['attr'], 'The firstname field must have a title.');
        self::assertSame('form.firstname.title', $options['attr']['title'], 'The firstname field title must be "form.firstname.title".');
        self::assertArrayHasKey('empty_data', $options, 'The firstname field must have a empty_data option.');
        self::assertSame('', $options['empty_data'], 'The firstname field empty_data must be an empty string.');
        self::assertArrayHasKey('label', $options, 'The firstname field must have a label option.');
        self::assertSame('form.firstname.label', $options['label'], 'The firstname field label must be "form.firstname.label".');
    }

    /**
     * Asserts that the lastname input is present.
     * @param \Symfony\Component\Form\FormBuilder $builder the form builder.
     */
    private function assertHasLastnameInput(FormBuilder $builder): void
    {
        $field = $builder->get('lastname');
        $fieldClass = get_class($field->getType()->getInnerType());

        self::assertSame('lastname', $field->getName(), 'The lastname field must named "lastname".');
        self::assertSame(TextType::class, $fieldClass, 'The lastname field must be of type "' . TextType::class . '".');

        $options = $field->getOptions();

        self::assertArrayHasKey('attr', $options, 'The lastname field must have an attr option.');
        self::assertArrayHasKey('maxLength', $options['attr'], 'lastname field must have a maxLength.');
        self::assertSame(200, $options['attr']['maxLength'], 'The lastname field maxLength must be 200.');
        self::assertArrayHasKey('pattern', $options['attr'], 'The lastname field must have a pattern.');
        self::assertSame('^.+$', $options['attr']['pattern'], 'The lastname field pattern must be "^.+$".');
        self::assertArrayHasKey('placeholder', $options['attr'], 'The lastname field must have a placeholder.');
        self::assertSame('form.lastname.placeholder', $options['attr']['placeholder'], 'The lastname field placeholder must be "form.lastname.placeholder".');
        self::assertArrayHasKey('size', $options['attr'], 'The lastname field must have a size.');
        self::assertSame(30, $options['attr']['size'], 'The lastname field size must be 30.');
        self::assertArrayHasKey('title', $options['attr'], 'The lastname field must have a title.');
        self::assertSame('form.lastname.title', $options['attr']['title'], 'The lastname field title must be "form.lastname.title".');
        self::assertArrayHasKey('empty_data', $options, 'The lastname field must have a empty_data option.');
        self::assertSame('', $options['empty_data'], 'The lastname field empty_data must be an empty string.');
        self::assertArrayHasKey('label', $options, 'The lastname field must have a label option.');
        self::assertSame('form.lastname.label', $options['label'], 'The lastname field label must be "form.lastname.label".');
    }


    /**
     * Test that the options can be configured.
     */
    public function testCanUseEveryConfiguredOptions(): void
    {
        $form = new AuthorType();

        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'data_class',
            'translation_domain'
        ]);

        $form->configureOptions($resolver);

        self::assertEmpty($resolver->getMissingOptions(), 'The form must use every requirements.');
    }
}
