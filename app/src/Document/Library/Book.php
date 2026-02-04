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
    #[MongoDB\Id()]
    private ?string $id;

    /**
     * @var string the title.
     */
    #[MongoDB\Field()]
    private string $title;

    /**
     * @var \App\Document\Library\Author the author.
     */
    #[MongoDB\EmbedOne(targetDocument: Author::class)]
    private ?Author $author;


    // Magic methods :

    /**
     * The constructor.
     * @param string $title the title.
     * @param \App\Document\Library\Author $author the author.
     */
    public function __construct(string $title = '', ?Author $author = null)
    {
        $this->id = null;
        $this->title = $title;
        $this->author = $author;
    }


    // Accessors :

    /**
     * Returns the identifier.
     * @return string|null the identifier.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Returns the title.
     * @return string the title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns the author.
     * @return \App\Document\Library\Author|null the author.
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    // Mutators :

    /**
     * Changes the title.
     * @param string $title the title.
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Changes the author.
     * @param \App\Document\Library\Author $author the author.
     */
    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }
}
