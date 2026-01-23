<?php

declare(strict_types=1);

namespace App\Repository\Furniture;

use App\Entity\Furniture\Chair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * The chair repository.
 *
 * @psalm-suppress MissingTemplateParam
 */
final class ChairRepository extends ServiceEntityRepository
{
    // Magic methods :

    /**
     * The constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry the registry manager.
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chair::class);
    }
}
