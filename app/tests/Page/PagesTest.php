<?php

declare(strict_types=1);

namespace App\Tests\Page;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test the home page.
 *
 * @coversDefaultClass \App\Controller\PagesController
 */
class PagesTest extends WebTestCase
{
    // Methods :

    /**
     * Test that the homepage can be displayed.
     *
     * @covers ::home
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
     *
     * @covers ::home
     * @covers \App\Repository\ProductRepository::__construct
     * @uses \App\Controller\ProductController::index
     * @depends testCanShowHome
     */
    public function testCanBrowseToProductIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Show products');

        self::assertResponseIsSuccessful();
    }
}
