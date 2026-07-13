<?php

declare(strict_types=1);

namespace App\Tests\Page\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * The assertions for a product's price history.
 */
class ProductPricehistoryAssertion extends WebTestCase
{
    // Methods :

    /**
     * Asserts that a product's price history can be returned.
     * @param string $productUri the URI that returns the prices.
     */
    public function assertCanGetPriceHistory(string $productUri): void
    {
        $client = static::createClient();

        $client->request('GET', $productUri);
        $apiResponse = $client->getResponse()->getContent();

        self::assertResponseStatusCodeSame(200, 'GET "' . $productUri . '" failed.');
        self::assertJson($apiResponse);

        $prices = json_decode($apiResponse, true);

        self::assertArrayHasKey('2000-01-01', $prices);
        self::assertSame(10, $prices['2000-01-01']);
        self::assertArrayHasKey('2005-01-01', $prices);
        self::assertSame(11, $prices['2005-01-01']);
        self::assertArrayHasKey('2010-01-01', $prices);
        self::assertSame(14, $prices['2010-01-01']);
        self::assertArrayHasKey('2015-01-01', $prices);
        self::assertSame(15, $prices['2015-01-01']);
        self::assertArrayHasKey('2020-01-01', $prices);
        self::assertSame(12, $prices['2020-01-01']);
        self::assertArrayHasKey('2025-01-01', $prices);
        self::assertSame(16, $prices['2025-01-01']);
    }
}
