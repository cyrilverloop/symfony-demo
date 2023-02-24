<?php

declare(strict_types=1);

namespace App\Tests\Page\Product;

/**
 * Generates long string.
 */
trait GenerateString
{
    // Methods :

    /**
     * Returns a long string.
     * @param int $size the size of the string.
     * @return string a long string.
     */
    public static function generateLongString(int $size): string
    {
        $string = '';

        for ($stringMaxSize = 0; $stringMaxSize <= $size; $stringMaxSize++) {
            $string .= 'a';
        }

        return $string;
    }
}
