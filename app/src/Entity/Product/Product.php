<?php

declare(strict_types=1);

namespace App\Entity\Product;

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
class Product
{
    // Properties :

    /**
     * @var null|int the identifier/primary key.
     */
    #[ORM\Id]
    #[ORM\Column(
        options: ["unsigned" => true]
    )]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    public protected(set) ?int $id;


    // Magic methods :

    /**
     * The constructor.
     * @param string $name the name.
     * @param string|null $description the description.
     */
    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
        #[Assert\Length(
            max: 50,
            minMessage: 'product.name.minLength',
        )]
        #[Assert\NotBlank(message: 'product.name.notBlank')]
        public string $name = '',

        #[ORM\Column(type: Types::TEXT, nullable: true)]
        #[Assert\Length(
            max: 300,
            maxMessage: 'product.name.maxLength',
        )]
        public ?string $description = null
    ) {
        $this->id = null;
    }
}
