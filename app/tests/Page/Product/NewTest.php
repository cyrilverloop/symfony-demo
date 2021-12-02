<?php

declare(strict_types=1);

namespace App\Tests\Page\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Tests\Page\Product\GenerateString;
use App\Tests\Page\Product\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the product index page.
 *
 * @coversDefaultClass \App\Controller\ProductController
 */
class NewTest extends WebTestCase
{
    // Traits :
    use GenerateString;
    use ProductFixture;


    // Methods :

    /**
     * Tests that the page to create a new product
     * can be displayed.
     * @return void
     *
     * @covers ::new
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     */
    public function testCanDisplayNewProductPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Create new Product', 'There must be a <h1> for new products.');

        $form = $crawler->filter('form[name="product"]');
        self::assertCount(1, $form, 'There must be a form to create products.');
        $this->assertHasAnInputForName($form->filter('#product_name'));
        $this->assertHasAnInputForDescription($form->filter('#product_description'));
        self::assertNotEmpty($form->filter('#product__token')->attr('value'), 'The form must have a token.');
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
        self::assertSame("30", $nameInput->attr('size'), 'The name input size must be 30.');
        self::assertSame('The name of the product.', $nameInput->attr('title'), 'The name input title must be "The name of the product.".');
        self::assertSame('text', $nameInput->attr('type'), 'The name input must be of type text.');
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
    }


    /**
     * Tests that new products can be created.
     * @return void
     *
     * @covers ::new
     * @uses \App\Controller\ProductController::index
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     * @depends testCanDisplayNewProductPage
     */
    public function testCanCreateNewProduct(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/new');

        $form = $crawler->filter('#product_submit')->form();

        $productDatas = [
            'product[name]' => 'test-new-name',
            'product[description]' => 'test-new-description'
        ];

        $client->submit($form, $productDatas);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $product = $entityManager->find(Product::class, 1);

        self::assertSame(
            'test-new-name',
            $product->getName(),
            'The new product must be named "test-new-name".'
        );
        self::assertSame(
            'test-new-description',
            $product->getDescription(),
            'The new product must be named "test-new-description".'
        );
    }


    /**
     * Returns invalid product datas.
     * @return mixed invalid product datas.
     */
    public function getInvalidProductDatas(): array
    {
        $productDatas = [
            'product[name]' => 'test-new-name',
            'product[description]' => 'test-new-description'
        ];

        $emptyStringName = $productDatas;
        $emptyStringName['product[name]'] = '';

        $nameTooLong = $productDatas;
        $nameTooLong['product[name]'] = $this->generateLongString(51);

        $descriptionTooLong = $productDatas;
        $descriptionTooLong['product[name]'] = $this->generateLongString(301);

        return [
            'when the name is an empty string.' => [$emptyStringName],
            'when the name is too long (>50 chars).' => [$nameTooLong],
            'when the description is too long (>300 chars).' => [$descriptionTooLong]
        ];
    }

    /**
     * Tests that new products can not be created
     * with invalid product datas.
     * @param mixed[] $productDatas invalid product datas.
     * @return void
     *
     * @covers ::new
     * @uses \App\Controller\ProductController::index
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     * @dataProvider getInvalidProductDatas
     * @depends testCanDisplayNewProductPage
     */
    public function testCanNotCreateNewProduct(array $productDatas): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/new');

        $form = $crawler->filter('#product_submit')->form();

        $notSavedCrawler = $client->submit($form, $productDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        self::bootKernel();
        $productRepository = static::getContainer()->get(ProductRepository::class);
        $products = $productRepository->findAll();

        self::assertEmpty($products, 'The product must not be added.');
    }


    /**
     * Tests that a product name can be unique.
     * @return void
     *
     * @covers ::new
     * @uses \App\Controller\ProductController::index
     * @uses \App\Entity\Product
     * @uses \App\Form\ProductType
     * @uses \App\Repository\ProductRepository
     * @depends testCanDisplayNewProductPage
     */
    public function testAProductNameCanBeUnique(): void
    {
        $client = static::createClient();
        $this->addProductFixture();
        $crawler = $client->request('GET', '/product/new');

        $form = $crawler->filter('#product_submit')->form();

        $productDatas = [
            'product[name]' => 'test-name',
            'product[description]' => 'test-description'
        ];

        $notSavedCrawler = $client->submit($form, $productDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        self::bootKernel();
        $productRepository = static::getContainer()->get(ProductRepository::class);
        $products = $productRepository->findAll();

        self::assertCount(1, $products, 'The second product must not be added.');
    }
}
