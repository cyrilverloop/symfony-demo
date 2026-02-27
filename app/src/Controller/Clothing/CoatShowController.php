<?php

declare(strict_types=1);

namespace App\Controller\Clothing;

use App\Entity\Clothing\Coat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to show a coat.
 *
 * @psalm-api
 */
final class CoatShowController extends AbstractController
{
    // Methods :

    /**
     * Displays a coat informations.
     * @param \App\Entity\Clothing\Coat $coat the coat.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/coats/{id}',
        name: 'clothing_coat_show',
        /** @infection-ignore-all */
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function show(Coat $coat): Response
    {
        return $this->render('clothing/coat/show.html.twig', [
            'coat' => $coat,
        ]);
    }
}
