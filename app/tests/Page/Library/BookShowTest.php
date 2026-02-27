<?php

declare(strict_types=1);

namespace App\Tests\Page\Library;

use App\Controller\Library\BookDeleteController;
use App\Controller\Library\BookEditController;
use App\Controller\Library\BookIndexController;
use App\Controller\Library\BookShowController;
use App\Document\Library\Author;
use App\Document\Library\Book;
use App\Form\Library\AuthorType;
use App\Form\Library\BookType;
use App\Repository\Library\BookRepository;
use App\Tests\Page\Library\BookFixture;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test the book show page.
 */
#[
    PA\CoversClass(BookDeleteController::class),
    PA\CoversClass(BookShowController::class),
    PA\UsesClass(Author::class),
    PA\UsesClass(Book::class),
    PA\UsesClass(BookEditController::class),
    PA\UsesClass(BookIndexController::class),
    PA\UsesClass(BookRepository::class),
    PA\UsesClass(AuthorType::class),
    PA\UsesClass(BookType::class),
    PA\Group('pages'),
    PA\Group('pages_book'),
    PA\Group('pages_book_show'),
    PA\Group('book')
]
class BookShowTest extends WebTestCase
{
    // Traits :
    use BookFixture;


    // Methods :

    /**
     * Tests that book can be shown.
     */
    public function testCanShowBook(): void
    {
        $client = static::createClient();

        $bookId = $this->addBookFixture();

        $crawler = $client->request('GET', '/books/' . $bookId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Book');

        self::assertCount(
            1,
            $crawler->filter('.card'),
            'There must be 1 card on the book\'s page.'
        );
        self::assertSame(
            'test-title',
            $crawler->filter('h5.card-header')->text(),
            'The title of the book must be in a <h5>.'
        );

        $cardContent = $crawler->filter('.card-text');

        self::assertSame(
            'From test-firstname test-lastname',
            $cardContent->text()
        );

        $links = $crawler->filter('.card-body a');

        self::assertSame('back to list', $links->eq(0)->text(), 'There must be a "back to list" link.');
        self::assertStringContainsString(
            'btn-secondary',
            $links->eq(0)->attr('class'),
            'The back link must have a "btn-secondary" class.'
        );
        self::assertSame('edit', $links->eq(1)->text(), 'There must be an "edit" link.');
        self::assertStringContainsString(
            'btn-primary',
            $links->eq(1)->attr('class'),
            'The edit link must have a "btn-primary" class.'
        );

        $deleteButton = $crawler->filter('#delete_product_form button');

        self::assertSame('Delete', $deleteButton->text(), 'There must be a "Delete" button.');
        self::assertStringContainsString(
            'btn-primary',
            $deleteButton->attr('class'),
            'The delete button must have a "btn-primary" class.'
        );

        $this->removeBookFixture();
    }


    /**
     * Tests that the page can be browsed
     * back to the book index page.
     */
    #[PA\Depends('testCanShowBook')]
    public function testCanBrowseBackToTheIndex(): void
    {
        $client = static::createClient();

        $bookId = $this->addBookFixture();

        $crawler = $client->request('GET', '/books/' . $bookId);
        $backLink = $crawler->filter('.card-body a')->eq(0);

        $client->click($backLink->link());

        $this->assertResponseIsSuccessful();

        $this->removeBookFixture();
    }


    /**
     * Tests that the page can browsed
     * from the show to the edit page.
     */
    #[PA\Depends('testCanShowBook')]
    public function testCanBrowseFromShowToEdit(): void
    {
        $client = static::createClient();

        $bookId = $this->addBookFixture();

        $crawler = $client->request('GET', '/books/' . $bookId);
        $editLink = $crawler->filter('.card-body a')->eq(1);

        $client->click($editLink->link());

        $this->assertResponseIsSuccessful();

        $this->removeBookFixture();
    }


    /**
     * Tests that a book can be deleted.
     */
    #[PA\Depends('testCanShowBook')]
    public function testCanDeleteBook(): void
    {
        $client = static::createClient();

        $bookId = $this->addBookFixture();

        $crawler = $client->request('GET', '/books/' . $bookId);
        $form = $crawler->filter('#delete_product_form')->form();
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $documentManager = static::$kernel->getContainer()->get('doctrine_mongodb')->getManager();
        $deletedBook = $documentManager->find(Book::class, $bookId);

        self::assertNull($deletedBook, 'The Book has not been deleted.');

        $this->removeBookFixture();
    }
}
