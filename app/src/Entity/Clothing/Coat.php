<?php

declare(strict_types=1);

namespace App\Entity\Clothing;

use App\Entity\Product\Product;
use App\Repository\Clothing\CoatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * A coat entity.
 *
 * @psalm-api
 */
#[ORM\Entity(repositoryClass: CoatRepository::class)]
class Coat extends Product
{
}
