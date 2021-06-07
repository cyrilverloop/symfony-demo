<?php

declare(strict_types=1);

namespace App\Tests\Page\Product;

use App\Entity\Product;
use App\Tests\Page\Product\GenerateString;
use App\Tests\Page\Product\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the product edit page.
 *
 * @coversDefaultClass \App\Controller\ProductController
 */
class EditTest extends WebTestCase
{
    // Traits :
    use GenerateString;
    use ProductFixture;


    // Methods :

    /**
     * Tests that a product can be edited.
     * @return void
     *
     * @covers ::edit
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     */
    public function testCanDisplayProductEdit(): void
    {
        $client = static::createClient();
        $this->addProductFixture();
        $crawler = $client->request('GET', '/product/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Edit Product', 'There must be a <h1> for products.');

        $form = $crawler->filter('form[name="product"]');
        self::assertCount(1, $form, 'There must be a form to create products.');
        $this->assertHasAnInputForName($form->filter('#product_name'));
        $this->assertHasAnInputForDescription($form->filter('#product_description'));
        self::assertNotEmpty($form->filter('#product__token')->attr('value'), 'The create form must have a token.');
    }

    /**
     * The assertions for the input of the name.
     * @param \Symfony\Component\DomCrawler\Crawler $nameInput the crawler.
     * @return void
     */
    private function assertHasAnInputForName(Crawler $nameInput): void
    {
        self::assertSame('50', $nameInput->attr('maxlength'), 'The name input maxlength must be 50.');
        self::assertSame('product[name]', $nameInput->attr('name'), 'The name input must be named "product[name]".');
        self::assertSame('^.+$', $nameInput->attr('pattern'), 'The name input pattern must be "^.+$".');
        self::assertSame('The name', $nameInput->attr('placeholder'), 'The name input placeholder must be "The name".');
        self::assertSame('required', $nameInput->attr('required'), 'The name input must be required.');
        self::assertSame('30', $nameInput->attr('size'), 'The name input size must be 30.');
        self::assertSame('The name of the product.', $nameInput->attr('title'), 'The name input title must be "The name of the product.".');
        self::assertSame('text', $nameInput->attr('type'), 'The name input must be of type text.');
        self::assertSame('test-name', $nameInput->attr('value'), 'The name input must have "test-name" as a value.');
    }

    /**
     * The assertions for the input of the description.
     * @param \Symfony\Component\DomCrawler\Crawler $nameInput the crawler.
     * @return void
     */
    private function assertHasAnInputForDescription(Crawler $nameInput): void
    {
        self::assertSame('300', $nameInput->attr('maxlength'), 'The name input maxlength must be 300.');
        self::assertSame('product[description]', $nameInput->attr('name'), 'The name input must be named "product[description]".');
        self::assertSame('A description', $nameInput->attr('placeholder'), 'The name input placeholder must be "A description".');
        self::assertSame('The description of the product.', $nameInput->attr('title'), 'The name input title must be "The description of the product.".');
        self::assertSame('test-description', $nameInput->text(), 'The description input must have "test-description" as a value.');
    }


    /**
     * Tests that the page can be browsed
     * back to the product index page.
     * @return void
     *
     * @covers ::edit
     * @uses \App\Controller\ProductController::index
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     * @depends testCanDisplayProductEdit
     */
    public function testCanBrowseBackToTheIndex(): void
    {
        $client = static::createClient();
        $this->addProductFixture();
        $crawler = $client->request('GET', '/product/1/edit');
        $backLink = $crawler->filter('a.btn-secondary')->eq(0);

        $client->click($backLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that a product can be updated.
     * @return void
     *
     * @covers ::edit
     * @uses \App\Controller\ProductController::index
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     * @depends testCanDisplayProductEdit
     */
    public function testCanUpdateAProduct(): void
    {
        $client = static::createClient();
        $this->addProductFixture();
        $crawler = $client->request('GET', '/product/1/edit');
        $form = $crawler->filter('#product_submit')->form();

        $productDatas = [
            'product[name]' => 'test-update-name',
            'product[description]' => 'test-update-description'
        ];

        $client->submit($form, $productDatas);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $product = $entityManager->find(Product::class, 1);
        $entityManager->refresh($product);

        self::assertSame(
            'test-update-name',
            $product->getName(),
            'The new product must be named "test-update-name".'
        );
        self::assertSame(
            'test-update-description',
            $product->getDescription(),
            'The new product must be named "test-update-description".'
        );
    }


    /**
     * Returns invalid product datas.
     * @return mixed invalid product datas.
     */
    public function getInvalidProductDatas(): array
    {
        $productDatas = [
            'product[name]' => 'test-update-name',
            'product[description]' => 'test-update-description'
        ];

        $emptyStringName = $productDatas;
        $emptyStringName['product[name]'] = '';

        $nameTooLong = $productDatas;
        $nameTooLong['product[name]'] = $this->generateLongString(51);

        $descriptionTooLong = $productDatas;
        $descriptionTooLong['product[description]'] = $this->generateLongString(301);

        return [
            'when the name is an empty string.' => [$emptyStringName],
            'when the name is too long (>50 chars).' => [$nameTooLong],
            'when the description is too long (>300 chars).' => [$descriptionTooLong]
        ];
    }

    /**
     * Tests that products can not be updated
     * with invalid product datas.
     * @param mixed[] $productDatas invalid product datas.
     * @return void
     *
     * @covers ::edit
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     * @dataProvider getInvalidProductDatas
     * @depends testCanDisplayProductEdit
     */
    public function testCanNotUpdateAProduct(array $productDatas): void
    {
        $client = static::createClient();
        $this->addProductFixture();
        $crawler = $client->request('GET', '/product/1/edit');

        $form = $crawler->filter('#product_submit')->form();
        $notSavedCrawler = $client->submit($form, $productDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $product = $entityManager->find(Product::class, 1);
        $entityManager->refresh($product);

        self::assertNotEquals(
            $productDatas['product[name]'],
            $product->getName(),
            'The product name must not be updated.'
        );
        self::assertNotEquals(
            $productDatas['product[description]'],
            $product->getDescription(),
            'The product description must not be updated.'
        );
    }


    /**
     * Tests that a product name can be unique.
     * @return void
     *
     * @covers ::edit
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     * @depends testCanDisplayProductEdit
     */
    public function testAProductNameCanBeUnique(): void
    {
        $client = static::createClient();
        $this->addProductFixture();

        $product = new Product('test-name2', 'test-description2');

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        $crawler = $client->request('GET', '/product/2/edit');

        $form = $crawler->filter('form[name="product"]')->form();

        $productDatas = [
            'product[name]' => 'test-name',
            'product[description]' => 'test-description'
        ];

        $notSavedCrawler = $client->submit($form, $productDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');
    }


    /**
     * Tests that a product can be deleted.
     * @return void
     *
     * @covers ::delete
     * @covers ::edit
     * @uses \App\Controller\ProductController::index
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     * @depends testCanDisplayProductEdit
     */
    public function testCanDeleteProduct(): void
    {
        $client = static::createClient();

        $this->addProductFixture();

        $crawler = $client->request('GET', '/product/1/edit');
        $form = $crawler->filter('#delete_product_form')->form();
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
    }
}
