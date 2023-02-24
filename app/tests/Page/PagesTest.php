<?php

declare(strict_types=1);

namespace App\Tests\Page;

use App\Controller\PagesController;
use App\Controller\ProductController;
use App\Repository\ProductRepository;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test the home page.
 */
#[
    PA\CoversClass(PagesController::class),
    PA\UsesClass(ProductController::class),
    PA\UsesClass(ProductRepository::class),
    PA\Group('pages'),
    PA\Group('pages_home')
]
class PagesTest extends WebTestCase
{
    // Methods :

    /**
     * Test that the homepage can be displayed.
     */
    public function testCanShowHome(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Symfony Demo!');
        $this->assertSelectorTextContains('p', 'a simple Symfony demo');
    }

    /**
     * Test that a user can browse to the product list.
     */
    #[
        PA\Depends('testCanShowHome')
    ]
    public function testCanBrowseToProductIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Show products');

        self::assertResponseIsSuccessful();
    }
}
