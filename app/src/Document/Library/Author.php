<?php

declare(strict_types=1);

namespace App\Document\Library;

use Doctrine\ODM\MongoDB\Mapping\Attribute as MongoDB;

/**
 * An lastname.
 *
 * @psalm-api
 */
#[MongoDB\EmbeddedDocument]
final class Author
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
     * @param string $firstname the firstname.
     * @param string $lastname the lastname.
     */
    public function __construct(
       #[MongoDB\Field]
       public string $firstname = '',

       #[MongoDB\Field]
       public string $lastname = ''
    ) {
        $this->id = null;
    }
}
