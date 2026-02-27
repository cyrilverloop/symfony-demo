<?php

declare(strict_types=1);

namespace App\Controller\Furniture;

use App\Entity\Furniture\Chair;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to show a chair.
 *
 * @psalm-api
 */
final class ChairShowController extends AbstractController
{
    // Methods :

    /**
     * Displays a chair informations.
     * @param \App\Entity\Furniture\Chair $chair the chair.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/chairs/{id}',
        name: 'furniture_chair_show',
        /** @infection-ignore-all */
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function show(Chair $chair): Response
    {
        return $this->render('furniture/chair/show.html.twig', [
            'chair' => $chair,
        ]);
    }
}
