<?php

declare(strict_types=1);

namespace App\Repository\Library;

use App\Document\Library\Book;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

/**
 * The book repository.
 *
 * @psalm-suppress MissingTemplateParam
 */
final class BookRepository extends ServiceDocumentRepository
{
    // Magic methods :

    /**
     * The constructor.
     * @param \Doctrine\Bundle\MongoDBBundle\ManagerRegistry $registry the registry.
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
}
