<?php

declare(strict_types=1);

namespace App\Repository\Clothing;

use App\Entity\Clothing\Coat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * The coat repository.
 *
 * @psalm-suppress MissingTemplateParam
 */
final class CoatRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Coat::class);
    }
}
