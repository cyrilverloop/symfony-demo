<?php

declare(strict_types=1);

namespace App\Tests\Page\Clothing;

use App\Controller\Clothing\CoatPriceController;
use App\Service\Product\GetPriceHistory;
use App\Tests\Page\Product\ProductPricehistoryAssertion;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test the coat's price history.
 */
#[
    PA\CoversClass(CoatPriceController::class),
    PA\CoversClass(GetPriceHistory::class),
    PA\Group('pages'),
    PA\Group('pages_coat'),
    PA\Group('pages_coat_priceHistory'),
    PA\Group('coat')
]
final class CoatPricehistoryTest extends ProductPricehistoryAssertion
{
    // Methods :

    /**
     * Tests that a coat's price history can be shown returned.
     */
    public function testCanGetCoatPriceHistory(): void
    {
        $this->assertCanGetPriceHistory('/coats/1/price-history');
    }
}
