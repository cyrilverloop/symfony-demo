<?php

declare(strict_types=1);

namespace App\Controller\Clothing;

use App\Service\Product\GetPriceHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to get the coat price's history.
 *
 * @psalm-api
 */
final class CoatPriceController extends AbstractController
{
    // Magic methods :

    /**
     * The constructor.
     * @param \App\Service\Product\GetPriceHistory $getPriceHistory the product price service.
     */
    public function __construct(
        private GetPriceHistory $getPriceHistory
    ) {
    }


    // Methods :

    /**
     * Gets a coat price's history.
     * @param int $id the identifier.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/coats/{id}/price-history',
        name: 'clothing_coat_priceHistory',
        /** @infection-ignore-all */
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function get(int $id): Response
    {
        $coatPrices = $this->getPriceHistory->getPrice($id);

        return $this->json($coatPrices);
    }
}
