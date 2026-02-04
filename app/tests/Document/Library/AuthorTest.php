<?php

declare(strict_types=1);

namespace App\Tests\Document\Library;

use App\Document\Library\Author;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * A class to test the Author document.
 */
#[
    PA\CoversClass(Author::class),
    PA\Group('documents'),
    PA\Group('documents_author'),
    PA\Group('library')
]
final class AuthorTest extends TestCase
{
    // Methods :

    /**
     * Test that the identifier
     * is initialised to null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $author = new Author();

        self::assertNull($author->getId());
    }

    /**
     * Test that the firstname can be accessed.
     */
    public function testCanSetAndGetFirstname(): void
    {
        $author = new Author();
        $author->setFirstname('test-firstname');

        self::assertSame(
            'test-firstname',
            $author->getFirstname(),
            'The returned firstname is not the one that has been defined.'
        );
    }

    /**
     * Test that the lastname can be accessed.
     */
    public function testCanSetAndGetLastname(): void
    {
        $author = new Author();
        $author->setLastname('test-lastname');

        self::assertSame(
            'test-lastname',
            $author->getLastname(),
            'The returned lastname is not the one that has been defined.'
        );
    }
}
