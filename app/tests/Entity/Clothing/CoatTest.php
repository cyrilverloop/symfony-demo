<?php

declare(strict_types=1);

namespace App\Tests\Entity\Clothing;

use App\Entity\Clothing\Coat;
use App\Tests\Entity\Product\ProductTestcase;
use PHPUnit\Framework\Attributes as PA;

/**
 * A class to test the Coat entity.
 */
#[
    PA\CoversClass(Coat::class),
    PA\Group('entities'),
    PA\Group('entities_coat'),
    PA\Group('coat')
]
final class CoatTest extends ProductTestcase
{
    // Methods :

    /**
     * Initialises tests.
     */
    #[\Override()]
    public function setUp(): void
    {
        $this->product = new Coat('');
    }
}
