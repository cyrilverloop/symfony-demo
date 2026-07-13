<?php

declare(strict_types=1);

namespace App\Tests\Page\Furniture;

use App\Controller\Furniture\ChairEditController;
use App\Controller\Furniture\ChairNewController;
use App\Controller\Furniture\ChairIndexController;
use App\Controller\Furniture\ChairShowController;
use App\Entity\Furniture\Chair;
use App\Form\Furniture\ChairType;
use App\Repository\Furniture\ChairRepository;
use App\Tests\Page\Furniture\ChairFixture;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the chair index page.
 */
#[
    PA\CoversClass(ChairIndexController::class),
    PA\CoversClass(ChairRepository::class),
    PA\UsesClass(Chair::class),
    PA\UsesClass(ChairEditController::class),
    PA\UsesClass(ChairNewController::class),
    PA\UsesClass(ChairShowController::class),
    PA\UsesClass(ChairType::class),
    PA\Group('pages'),
    PA\Group('pages_chair'),
    PA\Group('pages_chair_index'),
    PA\Group('chair')
]
final class ChairIndexTest extends WebTestCase
{
    // Traits :
    use ChairFixture;


    // Methods :

    /**
     * Tests that chair index can be displayed without record.
     */
    public function testCanShowChairIndexWhenThereIsNoRecord(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/chairs/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Chair index');
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

        self::assertCount(5, $tableHeaders, 'There must be 5 table headers.');

        self::assertSame('Id', $tableHeaders->eq(0)->text(), 'The first header must be "Id".');
        self::assertSame('Name', $tableHeaders->eq(1)->text(), 'The second header must be "Name".');
        self::assertSame('Description', $tableHeaders->eq(2)->text(), 'The third header must be "Description".');
        self::assertSame('Price', $tableHeaders->eq(3)->text(), 'The fourth header must be "Price".');
        self::assertSame('actions', $tableHeaders->eq(4)->text(), 'The fifth header must be "actions".');
    }


    /**
     * Tests that chair index can be displayed with records.
     */
    public function testCanShowChairIndexWhenThereAreRecords(): void
    {
        $client = static::createClient();

        $this->addChairFixture();

        $crawler = $client->request('GET', '/chairs/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Chair index');
        $this->assertHasTable($crawler);

        $tableRows = $crawler->filter('tr');
        $tablesCount = count($tableRows) - 1;

        self::assertSame(1, $tablesCount, 'There must be 1 chair/row in the table.');

        $tableTds = $tableRows->eq(1)->filter('td');

        self::assertSame('1', $tableTds->eq(0)->text(), 'The table has an unexpected chair id.');
        self::assertSame('test-name', $tableTds->eq(1)->text(), 'The table has an unexpected chair name.');
        self::assertSame('test-description', $tableTds->eq(2)->text(), 'The table has an unexpected chair description.');
    }


    /**
     * Tests that the page can browsed
     * from the index to the "new" page.
     */
    public function testCanBrowseFromIndexToCreateNew(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/chairs/');
        $newLink = $crawler->filter('a')->last();

        self::assertSame('Create new', $newLink->text(), 'The text of the button must be "Create new".');

        $client->click($newLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "show" page.
     */
    #[PA\Depends('testCanShowChairIndexWhenThereAreRecords')]
    public function testCanBrowseFromIndexToShow(): void
    {
        $client = static::createClient();

        $this->addChairFixture();

        $crawler = $client->request('GET', '/chairs/');

        $this->assertResponseIsSuccessful();

        $table = $crawler->filter('tr')->eq(1);
        $tableActions = $table->filter('td')->eq(4);
        $showLink = $tableActions->filter('a')->eq(0);

        self::assertSame('show', $showLink->text(), 'The text of the button must be "show".');

        $client->click($showLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "show" page.
     */
    #[PA\Depends('testCanShowChairIndexWhenThereAreRecords')]
    public function testCanBrowseFromIndexToEdit(): void
    {
        $client = static::createClient();

        $this->addChairFixture();

        $crawler = $client->request('GET', '/chairs/');

        $this->assertResponseIsSuccessful();

        $table = $crawler->filter('tr')->eq(1);
        $tableActions = $table->filter('td')->eq(4);
        $showLink = $tableActions->filter('a')->eq(1);

        self::assertSame('edit', $showLink->text(), 'The text of the button must be "edit".');

        $client->click($showLink->link());

        $this->assertResponseIsSuccessful();
    }
}
