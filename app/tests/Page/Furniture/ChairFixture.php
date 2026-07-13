<?php

declare(strict_types=1);

namespace App\Tests\Page\Furniture;

use App\Entity\Furniture\Chair;

/**
 * A trait to add a chair fixture.
 */
trait ChairFixture
{
    // Methods :

    /**
     * Adds a chair fixture
     * to the database.
     */
    public function addChairFixture(): void
    {
        $chair = new Chair(
            'test-name',
            'test-description',
            5
        );

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($chair);
        $entityManager->flush();
    }
}
