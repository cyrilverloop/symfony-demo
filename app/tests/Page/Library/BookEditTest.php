<?php

declare(strict_types=1);

namespace App\Tests\Page\Library;

use App\Controller\Library\BookDeleteController;
use App\Controller\Library\BookEditController;
use App\Controller\Library\BookIndexController;
use App\Document\Library\Author;
use App\Document\Library\Book;
use App\Form\Library\AuthorType;
use App\Form\Library\BookType;
use App\Repository\Library\BookRepository;
use App\Tests\Page\Library\BookFixture;
use App\Tests\Page\Product\GenerateString;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the book edit page.
 */
#[
    PA\CoversClass(BookDeleteController::class),
    PA\CoversClass(BookEditController::class),
    PA\UsesClass(Author::class),
    PA\UsesClass(Book::class),
    PA\UsesClass(BookIndexController::class),
    PA\UsesClass(BookRepository::class),
    PA\UsesClass(AuthorType::class),
    PA\UsesClass(BookType::class),
    PA\Group('pages'),
    PA\Group('pages_book'),
    PA\Group('pages_book_edit'),
    PA\Group('book')
]
class BookEditTest extends WebTestCase
{
    // Traits :
    use GenerateString;
    use BookFixture;


    // Methods :

    /**
     * Tests that a book can be edited.
     */
    public function testCanDisplayBookEdit(): void
    {
        $client = static::createClient();
        $bookId = $this->addBookFixture();
        $crawler = $client->request('GET', '/books/' . $bookId . '/edit');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Edit book', 'There must be a <h1> for books.');

        $form = $crawler->filter('form[name="book"]');
        self::assertCount(1, $form, 'There must be a form to create books.');
        $this->assertHasAnInputForName($form->filter('#book_title'));
        $this->assertHasAnInputForAuthorFirstName($form->filter('#book_author_firstname'));
        $this->assertHasAnInputForAuthorLastName($form->filter('#book_author_lastname'));
        self::assertNotEmpty($form->filter('#book__token')->attr('value'), 'The create form must have a token.');

        $this->removeBookFixture();
    }

    /**
     * The assertions for the input of the title.
     * @param \Symfony\Component\DomCrawler\Crawler $titleInput the crawler.
     */
    private function assertHasAnInputForName(Crawler $titleInput): void
    {
        self::assertSame('200', $titleInput->attr('maxlength'), 'The title input maxlength must be 200.');
        self::assertSame('book[title]', $titleInput->attr('name'), 'The title input must be named "book[title]".');
        self::assertSame('^.+$', $titleInput->attr('pattern'), 'The title input pattern must be "^.+$".');
        self::assertSame('The title', $titleInput->attr('placeholder'), 'The title input placeholder must be "The title".');
        self::assertSame('required', $titleInput->attr('required'), 'The title input must be required.');
        self::assertSame('30', $titleInput->attr('size'), 'The title input size must be 30.');
        self::assertSame('The title of the book.', $titleInput->attr('title'), 'The title input title must be "The title of the book.".');
        self::assertSame('text', $titleInput->attr('type'), 'The title input must be of type text.');
        self::assertSame('test-title', $titleInput->attr('value'), 'The title input must have "test-title" as a value.');
    }

    /**
     * The assertions for the input of the author's first name.
     * @param \Symfony\Component\DomCrawler\Crawler $firstnameInput the crawler.
     */
    private function assertHasAnInputForAuthorFirstName(Crawler $firstnameInput): void
    {
        self::assertSame('200', $firstnameInput->attr('maxlength'), 'The firstname input maxlength must be 200.');
        self::assertSame('book[author][firstname]', $firstnameInput->attr('name'), 'The firstname input must be named "book[author][firstname]".');
        self::assertSame('^.+$', $firstnameInput->attr('pattern'), 'The firstname input pattern must be "^.+$".');
        self::assertSame('The first name', $firstnameInput->attr('placeholder'), 'The firstname input placeholder must be "The first name".');
        self::assertSame('required', $firstnameInput->attr('required'), 'The firstname input must be required.');
        self::assertSame('30', $firstnameInput->attr('size'), 'The firstname input size must be 30.');
        self::assertSame('The first name of the author.', $firstnameInput->attr('title'), 'The firstname input title must be "The first name of the author.".');
        self::assertSame('text', $firstnameInput->attr('type'), 'The firstname input must be of type text.');
        self::assertSame('test-firstname', $firstnameInput->attr('value'), 'The firstname input must have "test-firstname" as a value.');
    }

    /**
     * The assertions for the input of the author's last name.
     * @param \Symfony\Component\DomCrawler\Crawler $lastnameInput the crawler.
     */
    private function assertHasAnInputForAuthorLastName(Crawler $lastnameInput): void
    {
        self::assertSame('200', $lastnameInput->attr('maxlength'), 'The lastname input maxlength must be 200.');
        self::assertSame('book[author][lastname]', $lastnameInput->attr('name'), 'The lastname input must be named "book[author][lastname]".');
        self::assertSame('^.+$', $lastnameInput->attr('pattern'), 'The lastname input pattern must be "^.+$".');
        self::assertSame('The last name', $lastnameInput->attr('placeholder'), 'The lastname input placeholder must be "The last name".');
        self::assertSame('required', $lastnameInput->attr('required'), 'The lastname input must be required.');
        self::assertSame('30', $lastnameInput->attr('size'), 'The lastname input size must be 30.');
        self::assertSame('The last name of the author.', $lastnameInput->attr('title'), 'The lastname input title must be "The last name of the author.".');
        self::assertSame('text', $lastnameInput->attr('type'), 'The lastname input must be of type text.');
        self::assertSame('test-lastname', $lastnameInput->attr('value'), 'The lastname input must have "test-lastname" as a value.');
    }


    /**
     * Tests that the page can be browsed
     * back to the book index page.
     */
    #[PA\Depends('testCanDisplayBookEdit')]
    public function testCanBrowseBackToTheIndex(): void
    {
        $client = static::createClient();
        $bookId = $this->addBookFixture();
        $crawler = $client->request('GET', '/books/' . $bookId . '/edit');
        $backLink = $crawler->filter('a.btn-secondary')->eq(0);

        $client->click($backLink->link());

        $this->assertResponseIsSuccessful();

        $this->removeBookFixture();
    }


    /**
     * Tests that a book can be updated.
     */
    #[PA\Depends('testCanDisplayBookEdit')]
    public function testCanUpdateABook(): void
    {
        $client = static::createClient();
        $bookId = $this->addBookFixture();
        $crawler = $client->request('GET', '/books/' . $bookId . '/edit');
        $form = $crawler->filter('#book_submit')->form();

        $bookDatas = [
            'book[title]' => 'test-update-title',
            'book[author][firstname]' => 'test-update-author-firstname',
            'book[author][lastname]' => 'test-update-author-lastname'
        ];

        $client->submit($form, $bookDatas);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine_mongodb')->getManager();
        $book = $entityManager->find(Book::class, $bookId);
        $entityManager->refresh($book);

        self::assertSame(
            'test-update-title',
            $book->getTitle(),
            'The new book must be named "test-update-title".'
        );

        self::assertSame(
            'test-update-author-firstname',
            $book->getAuthor()->getFirstname(),
            'The new author first name must be "test-update-author-firstname".'
        );

        self::assertSame(
            'test-update-author-lastname',
            $book->getAuthor()->getLastname(),
            'The new author last name must be "test-update-author-lastname".'
        );

        $this->removeBookFixture();
    }


    /**
     * Tests that a book can be deleted.
     */
    #[PA\Depends('testCanDisplayBookEdit')]
    public function testCanDeleteBook(): void
    {
        $client = static::createClient();

        $bookId = $this->addBookFixture();

        $crawler = $client->request('GET', '/books/' . $bookId . '/edit');
        $form = $crawler->filter('#delete_product_form')->form();
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine_mongodb')->getManager();
        $deletedBook = $entityManager->find(Book::class, $bookId);

        self::assertNull($deletedBook, 'The Book has not been deleted.');

        $this->removeBookFixture();
    }
}
