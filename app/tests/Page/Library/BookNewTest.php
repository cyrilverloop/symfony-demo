<?php

declare(strict_types=1);

namespace App\Tests\Page\Library;

use App\Controller\Library\BookIndexController;
use App\Controller\Library\BookNewController;
use App\Document\Library\Author;
use App\Document\Library\Book;
use App\Form\Library\AuthorType;
use App\Form\Library\BookType;
use App\Repository\Library\BookRepository;
use App\Tests\Page\Product\GenerateString;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the book index page.
 */
#[
    PA\CoversClass(BookNewController::class),
    PA\UsesClass(Author::class),
    PA\UsesClass(Book::class),
    PA\UsesClass(BookIndexController::class),
    PA\UsesClass(BookRepository::class),
    PA\UsesClass(AuthorType::class),
    PA\UsesClass(BookType::class),
    PA\Group('pages'),
    PA\Group('pages_book'),
    PA\Group('pages_book_new'),
    PA\Group('book')
]
class BookNewTest extends WebTestCase
{
    // Traits :
    use GenerateString;
    use BookFixture;


    // Methods :

    /**
     * Tests that the page to create a new book
     * can be displayed.
     */
    public function testCanDisplayNewBookPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/books/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Create new book', 'There must be a <h1> for new books.');

        $form = $crawler->filter('form[name="book"]');
        self::assertCount(1, $form, 'There must be a form to create books.');
        $this->assertHasAnInputForTitle($form->filter('#book_title'));
        $this->assertHasAnInputForAuthorFirstName($form->filter('#book_author_firstname'));
        $this->assertHasAnInputForAuthorLastName($form->filter('#book_author_lastname'));
        self::assertNotEmpty($form->filter('#book__token')->attr('value'), 'The form must have a token.');
    }

    /**
     * The assertions for the input of the title.
     * @param \Symfony\Component\DomCrawler\Crawler $titleInput the crawler.
     */
    private function assertHasAnInputForTitle(Crawler $titleInput): void
    {
        self::assertSame('200', $titleInput->attr('maxlength'), 'The title input maxlength must be 200.');
        self::assertSame('book[title]', $titleInput->attr('name'), 'The title input must be named "book[title]".');
        self::assertSame('^.+$', $titleInput->attr('pattern'), 'The title input pattern must be "^.+$".');
        self::assertSame('The title', $titleInput->attr('placeholder'), 'The title input placeholder must be "The title".');
        self::assertSame('required', $titleInput->attr('required'), 'The title input must be required.');
        self::assertSame("30", $titleInput->attr('size'), 'The title input size must be 30.');
        self::assertSame('The title of the book.', $titleInput->attr('title'), 'The title input title must be "The title of the book.".');
        self::assertSame('text', $titleInput->attr('type'), 'The title input must be of type text.');
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
        self::assertNull($firstnameInput->attr('value'), 'The firstname input must be null.');
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
        self::assertNull($lastnameInput->attr('value'), 'The lastname input must ba null.');
    }


    /**
     * Tests that new books can be created.
     */
    #[PA\Depends('testCanDisplayNewBookPage')]
    public function testCanCreateNewBook(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/books/new');

        $form = $crawler->filter('#book_submit')->form();

        $bookDatas = [
            'book[title]' => 'test-new-title',
            'book[author][firstname]' => 'test-new-author-firstname',
            'book[author][lastname]' => 'test-new-author-lastname'
        ];

        $client->submit($form, $bookDatas);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        self::bootKernel();
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $books = $bookRepository->findAll();
        self::assertCount(1, $books);

        self::assertSame(
            'test-new-title',
            $books[0]->title,
            'The new book must be named "test-new-title".'
        );

        self::assertSame(
            'test-new-author-firstname',
            $books[0]->author->firstname,
            'The new author first name must be "test-new-author-firstname".'
        );

        self::assertSame(
            'test-new-author-lastname',
            $books[0]->author->lastname,
            'The new author last name must be "test-new-author-lastname".'
        );

        $this->removeBookFixture();
    }
}
