<?php

declare(strict_types=1);

namespace App\Tests\Document\Library;

use App\Document\Library\Author;
use App\Document\Library\Book;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * A class to test the Book document.
 */
#[
    PA\CoversClass(Book::class),
    PA\UsesClass(Author::class),
    PA\Group('documents'),
    PA\Group('documents_book'),
    PA\Group('library')
]
final class BookTest extends TestCase
{
    // Methods :

    /**
     * Test that the identifier
     * is initialised to null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $book = new Book();

        self::assertNull($book->id);
    }

    /**
     * Test that the title can be accessed.
     */
    public function testCanSetAndGetTitle(): void
    {
        $book = new Book();
        $book->title = 'test-title';

        self::assertSame(
            'test-title',
            $book->title,
            'The returned title is not the one that has been defined.'
        );
    }

    /**
     * Test that the author can be accessed.
     */
    public function testCanSetAndGetAuthor(): void
    {
        $book = new Book();
        $author = new Author();
        $book->author = $author;

        self::assertSame(
            $author,
            $book->author,
            'The returned author is not the one that has been defined.'
        );
    }
}
