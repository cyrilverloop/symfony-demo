<?php

declare(strict_types=1);

namespace App\Tests\Entity\Furniture;

use App\Entity\Furniture\Chair;
use App\Tests\Entity\Product\ProductTestcase;
use PHPUnit\Framework\Attributes as PA;

/**
 * A class to test the Chair entity.
 */
#[
    PA\CoversClass(Chair::class),
    PA\Group('entities'),
    PA\Group('entities_chair'),
    PA\Group('chair')
]
final class ChairTest extends ProductTestcase
{
    // Methods :

    /**
     * Initialises tests.
     */
    #[\Override()]
    public function setUp(): void
    {
        $this->product = new Chair('');
    }
}
