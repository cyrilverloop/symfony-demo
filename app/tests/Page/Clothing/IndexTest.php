<?php

declare(strict_types=1);

namespace App\Tests\Page\Clothing;

use App\Controller\Clothing\CoatController;
use App\Entity\Clothing\Coat;
use App\Form\Clothing\CoatType;
use App\Repository\Clothing\CoatRepository;
use App\Tests\Page\Clothing\CoatFixture;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the coat index page.
 */
#[
    PA\CoversClass(CoatController::class),
    PA\CoversClass(CoatRepository::class),
    PA\UsesClass(Coat::class),
    PA\UsesClass(CoatType::class),
    PA\Group('pages'),
    PA\Group('pages_coat'),
    PA\Group('pages_coat_index'),
    PA\Group('coat')
]
final class IndexTest extends WebTestCase
{
    // Traits :
    use CoatFixture;


    // Methods :

    /**
     * Tests that coat index can be displayed without record.
     */
    public function testCanShowCoatIndexWhenThereIsNoRecord(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/coats/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Coat index');
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
        self::assertSame('Name', $tableHeaders->eq(1)->text(), 'The second header must be "Name".');
        self::assertSame('Description', $tableHeaders->eq(2)->text(), 'The third header must be "Description".');
        self::assertSame('actions', $tableHeaders->eq(3)->text(), 'The fourth header must be "actions".');
    }


    /**
     * Tests that coat index can be displayed with records.
     */
    public function testCanShowCoatIndexWhenThereAreRecords(): void
    {
        $client = static::createClient();

        $this->addCoatFixture();

        $crawler = $client->request('GET', '/coats/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Coat index');

        $coatRows = $crawler->filter('tr');
        $coatsCount = count($coatRows) - 1;

        self::assertSame(1, $coatsCount, 'There must be 1 coat/row in the table.');

        $coatTds = $coatRows->eq(1)->filter('td');

        self::assertSame('1', $coatTds->eq(0)->text(), 'The table has an unexpected coat id.');
        self::assertSame('test-name', $coatTds->eq(1)->text(), 'The table has an unexpected coat name.');
        self::assertSame('test-description', $coatTds->eq(2)->text(), 'The table has an unexpected coat description.');
    }


    /**
     * Tests that the page can browsed
     * from the index to the "new" page.
     */
    public function testCanBrowseFromIndexToCreateNew(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/coats/');
        $newLink = $crawler->filter('a')->last();

        self::assertSame('Create new', $newLink->text(), 'The text of the button must be "Create new".');

        $client->click($newLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "show" page.
     */
    #[PA\Depends('testCanShowCoatIndexWhenThereAreRecords')]
    public function testCanBrowseFromIndexToShow(): void
    {
        $client = static::createClient();

        $this->addCoatFixture();

        $crawler = $client->request('GET', '/coats/');

        $this->assertResponseIsSuccessful();

        $coat = $crawler->filter('tr')->eq(1);
        $coatActions = $coat->filter('td')->eq(3);
        $showLink = $coatActions->filter('a')->eq(0);

        self::assertSame('show', $showLink->text(), 'The text of the button must be "show".');

        $client->click($showLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "show" page.
     */
    #[PA\Depends('testCanShowCoatIndexWhenThereAreRecords')]
    public function testCanBrowseFromIndexToEdit(): void
    {
        $client = static::createClient();

        $this->addCoatFixture();

        $crawler = $client->request('GET', '/coats/');

        $this->assertResponseIsSuccessful();

        $coat = $crawler->filter('tr')->eq(1);
        $coatActions = $coat->filter('td')->eq(3);
        $showLink = $coatActions->filter('a')->eq(1);

        self::assertSame('edit', $showLink->text(), 'The text of the button must be "edit".');

        $client->click($showLink->link());

        $this->assertResponseIsSuccessful();
    }
}
