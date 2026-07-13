<?php

declare(strict_types=1);

namespace App\Service\Product;

/**
 * A service that returns
 * the price history
 * of a product.
 */
final class GetPriceHistory
{
    // Methods :

    /**
     * Gets a product price's history.
     * @param int $id the identifier.
     * @return array the prices.
     *
     * @psalm-suppress UnusedParam $id
     */
    public function getPrice(int $id): array
    {
        return [
            '2000-01-01' => 10,
            '2005-01-01' => 11,
            '2010-01-01' => 14,
            '2015-01-01' => 15,
            '2020-01-01' => 12,
            '2025-01-01' => 16
        ];
    }
}
