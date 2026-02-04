<?php

declare(strict_types=1);

namespace App\Tests\Form\Library;

use App\Document\Library\Book;
use App\Form\Library\AuthorType;
use App\Form\Library\BookType;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A class to test the book form.
 */
#[
    PA\CoversClass(BookType::class),
    PA\UsesClass(AuthorType::class),
    PA\UsesClass(Book::class),
    PA\Group('forms'),
    PA\Group('forms_bookType'),
    PA\Group('book')
]
final class BookTypeTest extends TypeTestCase
{
    // Methods :

    /**
     * Test that the form can be built.
     */
    public function testCanBuildForm(): void
    {
        $builder = new FormBuilder('book', BookType::class, new EventDispatcher(), $this->factory);

        $form = new BookType();
        $form->buildForm($builder, []);

        $this->assertHasTitleInput($builder);
        $this->assertHasAuthorInput($builder);
        $this->assertHasSubmitButton($builder);
    }

    /**
     * Asserts that the title input is present.
     * @param \Symfony\Component\Form\FormBuilder $builder the form builder.
     */
    private function assertHasTitleInput(FormBuilder $builder): void
    {
        $field = $builder->get('title');
        $fieldClass = get_class($field->getType()->getInnerType());

        self::assertSame('title', $field->getName(), 'The title field must named "title".');
        self::assertSame(TextType::class, $fieldClass, 'The title field must be of type "' . TextType::class . '".');

        $options = $field->getOptions();

        self::assertArrayHasKey('attr', $options, 'The title field must have an attr option.');
        self::assertArrayHasKey('maxLength', $options['attr'], 'title field must have a maxLength.');
        self::assertSame(200, $options['attr']['maxLength'], 'The title field maxLength must be 200.');
        self::assertArrayHasKey('pattern', $options['attr'], 'The title field must have a pattern.');
        self::assertSame('^.+$', $options['attr']['pattern'], 'The title field pattern must be "^.+$".');
        self::assertArrayHasKey('placeholder', $options['attr'], 'The title field must have a placeholder.');
        self::assertSame('form.title.placeholder', $options['attr']['placeholder'], 'The title field placeholder must be "form.title.placeholder".');
        self::assertArrayHasKey('size', $options['attr'], 'The title field must have a size.');
        self::assertSame(30, $options['attr']['size'], 'The title field size must be 30.');
        self::assertArrayHasKey('title', $options['attr'], 'The title field must have a title.');
        self::assertSame('form.title.title', $options['attr']['title'], 'The title field title must be "form.title.title".');
        self::assertArrayHasKey('empty_data', $options, 'The title field must have a empty_data option.');
        self::assertSame('', $options['empty_data'], 'The title field empty_data must be an empty string.');
        self::assertArrayHasKey('label', $options, 'The title field must have a label option.');
        self::assertSame('form.title.label', $options['label'], 'The title field label must be "form.title.label".');
    }

    /**
     * Asserts that the author input is present.
     * @param \Symfony\Component\Form\FormBuilder $builder the form builder.
     */
    private function assertHasAuthorInput(FormBuilder $builder): void
    {
        $field = $builder->get('author');
        $fieldClass = get_class($field->getType()->getInnerType());

        self::assertSame('author', $field->getName(), 'The author field must named "author".');
        self::assertSame(AuthorType::class, $fieldClass, 'The author field must be of type "' . AuthorType::class . '".');
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
        $form = new BookType();

        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'data_class',
            'translation_domain'
        ]);

        $form->configureOptions($resolver);

        self::assertEmpty($resolver->getMissingOptions(), 'The form must use every requirements.');
    }
}
