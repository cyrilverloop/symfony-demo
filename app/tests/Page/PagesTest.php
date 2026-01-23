<?php

declare(strict_types=1);

namespace App\Tests\Page;

use App\Controller\PagesController;
use App\Controller\Clothing\CoatController;
use App\Controller\Furniture\ChairController;
use App\Repository\Clothing\CoatRepository;
use App\Repository\Furniture\ChairRepository;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test the home page.
 */
#[
    PA\CoversClass(PagesController::class),
    PA\UsesClass(CoatController::class),
    PA\UsesClass(CoatRepository::class),
    PA\UsesClass(ChairController::class),
    PA\UsesClass(ChairRepository::class),
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
     * Test that a user can browse to the chair list.
     */
    #[
        PA\Depends('testCanShowHome')
    ]
    public function testCanBrowseToChairIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Show chairs');

        self::assertResponseIsSuccessful();
    }

    /**
     * Test that a user can browse to the coat list.
     */
    #[
        PA\Depends('testCanShowHome')
    ]
    public function testCanBrowseToCoatIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Show coats');

        self::assertResponseIsSuccessful();
    }
}
