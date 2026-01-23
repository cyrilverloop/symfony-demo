<?php

declare(strict_types=1);

namespace App\Entity\Furniture;

use App\Entity\Product\Product;
use App\Repository\Furniture\ChairRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * A chair entity.
 *
 * @psalm-api
 */
#[
    ORM\Entity(repositoryClass: ChairRepository::class),
]
class Chair extends Product
{
}
