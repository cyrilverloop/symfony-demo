<?php

declare(strict_types=1);

namespace App\Tests\Page\Library;

use App\Document\Library\Author;
use App\Document\Library\Book;

/**
 * A trait to add a book fixture.
 */
trait BookFixture
{
    // Methods :

    /**
     * Adds a book fixture
     * to the database.
     * @return string the identifier of the book.
     */
    public function addBookFixture(): string
    {
        $author = new Author('test-firstname', 'test-lastname');
        $book = new Book('test-title', $author);

        $documentManager = static::$kernel->getContainer()->get('doctrine_mongodb')->getManager();
        $documentManager->persist($book);
        $documentManager->flush();

        return $book->getId();
    }

    /**
     * Removes a book fixture
     * to the database.
     */
    public function removeBookFixture(): void
    {
        $connection = static::$kernel->getContainer()->get('doctrine_mongodb')->getConnection();
        $connection->dropDatabase('symfony-demo-test');
    }
}
