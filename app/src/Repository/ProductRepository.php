<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * The product repository.
 */
final class ProductRepository extends ServiceEntityRepository
{
    // Magic methods :

    /**
     * The constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry the registry manager.
     *
     * @psalm-suppress PossiblyUnusedMethod
     * @psalm-suppress UnusedParam
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
}
