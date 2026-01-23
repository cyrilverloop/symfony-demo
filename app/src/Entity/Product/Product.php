<?php

declare(strict_types=1);

namespace App\Entity\Product;

use CyrilVerloop\DoctrineEntities\AbstractIntId;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A product entity.
 *
 * @psalm-api
 */
#[ORM\MappedSuperclass]
#[UniqueEntity(fields: 'name', message: 'product.name.uniqueEntity')]
class Product extends AbstractIntId
{
    // Properties :

    /**
     * @var string the name.
     */
    #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
    #[Assert\Length(
        max: 50,
        minMessage: 'product.name.minLength',
    )]
    #[Assert\NotBlank(message: 'product.name.notBlank')]
    private string $name;

    /**
     * @var null|string the description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 300,
        maxMessage: 'product.name.maxLength',
    )]
    private ?string $description;


    // Magic methods :

    /**
     * The constructor.
     * @param string $name the name, defaults to an empty string.
     * @param null|string $description the description, defaults to null.
     */
    public function __construct(string $name = '', ?string $description = null)
    {
        parent::__construct();

        $this->name = $name;
        $this->description = $description;
    }


    // Accessors :

    /**
     * Returns the name.
     * @return string the name.
     *
     * @psalm-api
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the description.
     * @return null|string the description.
     *
     * @psalm-api
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    // Mutators :

    /**
     * Changes the name.
     * @param string $name the name.
     *
     * @psalm-api
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Changes the description.
     * @param null|string $description the description.
     *
     * @psalm-api
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
