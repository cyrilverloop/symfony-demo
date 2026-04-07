<?php

declare(strict_types=1);

namespace App\Document\Library;

use App\Repository\Library\BookRepository;
use Doctrine\ODM\MongoDB\Mapping\Attribute as MongoDB;

/**
 * A book.
 *
 * @psalm-api
 */
#[MongoDB\Document(repositoryClass: BookRepository::class)]
final class Book
{
    // Properties :

    /**
     * @var string|null the identifier.
     */
    #[MongoDB\Id]
    public private(set) ?string $id;


    // Magic methods :

    /**
     * The constructor.
     * @param string $title the title.
     * @param \App\Document\Library\Author $author the author.
     */
    public function __construct(
        #[MongoDB\Field]
        public string $title = '',

        #[MongoDB\EmbedOne(targetDocument: Author::class)]
        public ?Author $author = null
    ) {
        $this->id = null;
    }
}
