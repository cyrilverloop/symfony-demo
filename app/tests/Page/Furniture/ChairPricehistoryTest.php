<?php

declare(strict_types=1);

namespace App\Tests\Page\Furniture;

use App\Controller\Furniture\ChairPriceController;
use App\Service\Product\GetPriceHistory;
use App\Tests\Page\Product\ProductPricehistoryAssertion;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test the chair's price history.
 */
#[
    PA\CoversClass(ChairPriceController::class),
    PA\CoversClass(GetPriceHistory::class),
    PA\Group('pages'),
    PA\Group('pages_chair'),
    PA\Group('pages_chair_priceHistory'),
    PA\Group('chair')
]
final class ChairPricehistoryTest extends ProductPricehistoryAssertion
{
    // Methods :

    /**
     * Tests that a chair's price history can be shown returned.
     */
    public function testCanGetChairPriceHistory(): void
    {
        $this->assertCanGetPriceHistory('/chairs/1/price-history');
    }
}
