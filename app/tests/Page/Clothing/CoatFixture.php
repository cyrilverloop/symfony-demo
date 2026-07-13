<?php

declare(strict_types=1);

namespace App\Tests\Page\Clothing;

use App\Entity\Clothing\Coat;

/**
 * A trait to add a coat fixture.
 */
trait CoatFixture
{
    // Methods :

    /**
     * Adds a coat fixture
     * to the database.
     */
    public function addCoatFixture(): void
    {
        $coat = new Coat(
            'test-name',
            'test-description',
            5
        );

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager('clothing');
        $entityManager->persist($coat);
        $entityManager->flush();
    }
}
