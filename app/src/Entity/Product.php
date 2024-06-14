<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use CyrilVerloop\DoctrineEntities\IntId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A product entity.
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity(fields: 'name', message: 'product.name.uniqueEntity')]
class Product
{
    // Traits :
    use IntId;


    // Properties :

    /**
     * @var string the name.
     */
    #[ORM\Column(type: "string", length: 50, unique: true)]
    #[Assert\Length(
        max: 50,
        minMessage: 'product.name.minLength',
    )]
    #[Assert\NotBlank(message: 'product.name.notBlank')]
    private string $name;

    /**
     * @var null|string the description.
     */
    #[ORM\Column(type: "text", nullable: true)]
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
        $this->id = null;
        $this->name = $name;
        $this->description = $description;
    }


    // Accessors :

    /**
     * Returns the name.
     * @return string the name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the description.
     * @return null|string the description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    // Mutators :

    /**
     * Changes the name.
     * @param string $name the name.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Changes the description.
     * @param null|string $description the description.
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
