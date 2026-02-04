<?php

declare(strict_types=1);

namespace App\Tests\Page\Library;

use App\Controller\Library\BookController;
use App\Document\Library\Author;
use App\Document\Library\Book;
use App\Form\Library\AuthorType;
use App\Form\Library\BookType;
use App\Repository\Library\BookRepository;
use App\Tests\Page\Library\BookFixture;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the book index page.
 */
#[
    PA\CoversClass(BookController::class),
    PA\CoversClass(BookRepository::class),
    PA\UsesClass(Author::class),
    PA\UsesClass(Book::class),
    PA\UsesClass(AuthorType::class),
    PA\UsesClass(BookType::class),
    PA\Group('pages'),
    PA\Group('pages_book'),
    PA\Group('pages_book_index'),
    PA\Group('book')
]
final class IndexTest extends WebTestCase
{
    // Traits :
    use BookFixture;


    // Methods :

    /**
     * Tests that book index can be displayed without record.
     */
    public function testCanShowBookIndexWhenThereIsNoRecord(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/books/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Book index');
        $this->assertHasTable($crawler);
        $this->assertSelectorTextSame('table tr td', 'no records found');
    }

    /**
     * The assertions for the table.
     * @param \Symfony\Component\DomCrawler\Crawler $crawler the crawler.
     */
    private function assertHasTable(Crawler $crawler): void
    {
        $table = $crawler->filter('table');

        self::assertCount(1, $table, 'There must be a table.');

        $tableHeaders = $table->filter('tr th');

        self::assertCount(4, $tableHeaders, 'There must be 4 table headers.');

        self::assertSame('Id', $tableHeaders->eq(0)->text(), 'The first header must be "Id".');
        self::assertSame('Title', $tableHeaders->eq(1)->text(), 'The second header must be "Name".');
        self::assertSame('Author', $tableHeaders->eq(2)->text(), 'The third header must be "Author".');
        self::assertSame('actions', $tableHeaders->eq(3)->text(), 'The fourth header must be "actions".');
    }


    /**
     * Tests that book index can be displayed with records.
     */
    public function testCanShowBookIndexWhenThereAreRecords(): void
    {
        $client = static::createClient();

        $this->addBookFixture();

        $crawler = $client->request('GET', '/books/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Book index');

        $tableRows = $crawler->filter('tr');
        $tablesCount = count($tableRows) - 1;

        self::assertSame(1, $tablesCount, 'There must be 1 book/row in the table.');

        $tableTds = $tableRows->eq(1)->filter('td');

        self::assertNotSame('', $tableTds->eq(0)->text(), 'The table has an empty book id.');
        self::assertSame('test-title', $tableTds->eq(1)->text(), 'The table has an unexpected book name.');
        self::assertSame('test-firstname test-lastname', $tableTds->eq(2)->text(), 'The table has an unexpected book author.');

        $this->removeBookFixture();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "new" page.
     */
    public function testCanBrowseFromIndexToCreateNew(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/books/');
        $newLink = $crawler->filter('a')->last();

        self::assertSame('Create new', $newLink->text(), 'The text of the button must be "Create new".');

        $client->click($newLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "show" page.
     */
    #[PA\Depends('testCanShowBookIndexWhenThereAreRecords')]
    public function testCanBrowseFromIndexToShow(): void
    {
        $client = static::createClient();

        $this->addBookFixture();

        $crawler = $client->request('GET', '/books/');

        $this->assertResponseIsSuccessful();

        $table = $crawler->filter('tr')->eq(1);
        $tableActions = $table->filter('td')->eq(3);
        $showLink = $tableActions->filter('a')->eq(0);

        self::assertSame('show', $showLink->text(), 'The text of the button must be "show".');

        $client->click($showLink->link());

        $this->assertResponseIsSuccessful();

        $this->removeBookFixture();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "show" page.
     */
    #[PA\Depends('testCanShowBookIndexWhenThereAreRecords')]
    public function testCanBrowseFromIndexToEdit(): void
    {
        $client = static::createClient();

        $this->addBookFixture();

        $crawler = $client->request('GET', '/books/');

        $this->assertResponseIsSuccessful();

        $table = $crawler->filter('tr')->eq(1);
        $tableActions = $table->filter('td')->eq(3);
        $showLink = $tableActions->filter('a')->eq(1);

        self::assertSame('edit', $showLink->text(), 'The text of the button must be "edit".');

        $client->click($showLink->link());

        $this->assertResponseIsSuccessful();

        $this->removeBookFixture();
    }
}
