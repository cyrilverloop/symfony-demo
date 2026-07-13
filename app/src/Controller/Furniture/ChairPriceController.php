<?php

declare(strict_types=1);

namespace App\Controller\Furniture;

use App\Service\Product\GetPriceHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to get the chair price's history.
 *
 * @psalm-api
 */
final class ChairPriceController extends AbstractController
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
     * Gets a chair price's history.
     * @param int $id the identifier.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/chairs/{id}/price-history',
        name: 'furniture_chair_priceHistory',
        /** @infection-ignore-all */
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function get(int $id): Response
    {
        $chairPrices = $this->getPriceHistory->getPrice($id);

        return $this->json($chairPrices);
    }
}
