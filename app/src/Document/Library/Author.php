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
    #[MongoDB\Id()]
    private ?string $id;

    /**
     * @var string the firstname.
     */
    #[MongoDB\Field()]
    private string $firstname;

    /**
     * @var string the lastname.
     */
    #[MongoDB\Field()]
    private string $lastname;


    // Magic methods :

    /**
     * The constructor.
     * @param string $firstname the firstname.
     * @param string $lastname the lastname.
     */
    public function __construct(string $firstname = '', string $lastname = '')
    {
        $this->id = null;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
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
     * Returns the firstname.
     * @return string the firstname.
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Returns the lastname.
     * @return string the lastname.
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    // Mutators :

    /**
     * Changes the firstname.
     * @param string $firstname the firstname.
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * Changes the lastname.
     * @param string $lastname the lastname.
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }
}
