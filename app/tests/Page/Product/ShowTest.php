<?php

declare(strict_types=1);

namespace App\Tests\Page\Product;

use App\Controller\ProductController;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Tests\Page\Product\ProductFixture;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test the product show page.
 */
#[
    PA\CoversClass(ProductController::class),
    PA\UsesClass(Product::class),
    PA\UsesClass(ProductRepository::class),
    PA\UsesClass(ProductType::class),
    PA\Group('pages'),
    PA\Group('pages_product'),
    PA\Group('pages_product_show'),
    PA\Group('product')
]
class ShowTest extends WebTestCase
{
    // Traits :
    use ProductFixture;


    // Methods :

    /**
     * Tests that product can be shown.
     */
    public function testCanShowProduct(): void
    {
        $client = static::createClient();

        $this->addProductFixture();

        $crawler = $client->request('GET', '/product/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Product');

        self::assertCount(
            1,
            $crawler->filter('.card'),
            'There must be 1 card on the product\'s page.'
        );
        self::assertSame(
            'test-name',
            $crawler->filter('h5.card-header')->text(),
            'The name of the product must be in a <h5>.'
        );
        self::assertSame(
            'test-description',
            $crawler->filter('p.card-text')->text(),
            'The description of the product must be in a <p>.'
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
    }


    /**
     * Tests that the page can be browsed
     * back to the product index page.
     */
    #[PA\Depends('testCanShowProduct')]
    public function testCanBrowseBackToTheIndex(): void
    {
        $client = static::createClient();

        $this->addProductFixture();

        $crawler = $client->request('GET', '/product/1');
        $backLink = $crawler->filter('.card-body a')->eq(0);

        $client->click($backLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the show to the edit page.
     */
    #[PA\Depends('testCanShowProduct')]
    public function testCanBrowseFromShowToEdit(): void
    {
        $client = static::createClient();

        $this->addProductFixture();

        $crawler = $client->request('GET', '/product/1');
        $editLink = $crawler->filter('.card-body a')->eq(1);

        $client->click($editLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that a product can be deleted.
     */
    #[PA\Depends('testCanShowProduct')]
    public function testCanDeleteProduct(): void
    {
        $client = static::createClient();

        $this->addProductFixture();

        $crawler = $client->request('GET', '/product/1');
        $form = $crawler->filter('#delete_product_form')->form();
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $deletedProduct = $entityManager->find(Product::class, 1);

        self::assertNull($deletedProduct, 'The Product has not been deleted.');
    }
}
