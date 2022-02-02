<?php

declare(strict_types=1);

namespace App\Tests\Page\Product;

use App\Tests\Page\Product\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the product index page.
 *
 * @coversDefaultClass \App\Controller\ProductController
 */
class IndexTest extends WebTestCase
{
    // Traits :
    use ProductFixture;


    // Methods :

    /**
     * Tests that product index can be displayed without record.
     *
     * @covers ::index
     * @uses \App\Repository\ProductRepository::__construct
     */
    public function testCanShowProductIndexWhenThereIsNoRecord(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Product index');
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
     * Tests that product index can be displayed with records.
     *
     * @covers ::index
     * @uses \App\Entity\Product
     * @uses \App\Repository\ProductRepository::__construct
     */
    public function testCanShowProductIndexWhenThereAreRecords(): void
    {
        $client = static::createClient();

        $this->addProductFixture();

        $crawler = $client->request('GET', '/product/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Product index');

        $tableRows = $crawler->filter('tr');
        $productsCount = count($tableRows) - 1;

        self::assertSame(1, $productsCount, 'There must be 1 product/row in the table.');

        $productTds = $tableRows->eq(1)->filter('td');

        self::assertSame('1', $productTds->eq(0)->text(), 'The table has an unexpected product id.');
        self::assertSame('test-name', $productTds->eq(1)->text(), 'The table has an unexpected product name.');
        self::assertSame('test-description', $productTds->eq(2)->text(), 'The table has an unexpected product description.');
    }


    /**
     * Tests that the page can browsed
     * from the index to the "new" page.
     *
     * @covers ::index
     * @uses \App\Controller\ProductController::new
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     */
    public function testCanBrowseFromIndexToCreateNew(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/');
        $newLink = $crawler->filter('a')->last();

        self::assertSame('Create new', $newLink->text(), 'The text of the button must be "Create new".');

        $client->click($newLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "show" page.
     *
     * @covers ::index
     * @uses \App\Controller\ProductController::show
     * @uses \App\Entity\Product
     * @uses \App\Repository\ProductRepository::__construct
     * @depends testCanShowProductIndexWhenThereAreRecords
     */
    public function testCanBrowseFromIndexToShow(): void
    {
        $client = static::createClient();

        $this->addProductFixture();

        $crawler = $client->request('GET', '/product/');

        $this->assertResponseIsSuccessful();

        $product = $crawler->filter('tr')->eq(1);
        $productActions = $product->filter('td')->eq(3);
        $showLink = $productActions->filter('a')->eq(0);

        self::assertSame('show', $showLink->text(), 'The text of the button must be "show".');

        $client->click($showLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the index to the "show" page.
     *
     * @covers ::index
     * @uses \App\Controller\ProductController::edit
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository::__construct
     * @depends testCanShowProductIndexWhenThereAreRecords
     */
    public function testCanBrowseFromIndexToEdit(): void
    {
        $client = static::createClient();

        $this->addProductFixture();

        $crawler = $client->request('GET', '/product/');

        $this->assertResponseIsSuccessful();

        $product = $crawler->filter('tr')->eq(1);
        $productActions = $product->filter('td')->eq(3);
        $showLink = $productActions->filter('a')->eq(1);

        self::assertSame('edit', $showLink->text(), 'The text of the button must be "edit".');

        $client->click($showLink->link());

        $this->assertResponseIsSuccessful();
    }
}
