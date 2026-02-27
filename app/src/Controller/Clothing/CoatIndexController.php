<?php

declare(strict_types=1);

namespace App\Controller\Clothing;

use App\Repository\Clothing\CoatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to show the coat list.
 *
 * @psalm-api
 */
final class CoatIndexController extends AbstractController
{
    // Methods :

    /**
     * Displays the coat list.
     * @param \App\Repository\Clothing\CoatRepository $coatRepository the coat repository.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/coats/',
        name: 'clothing_coat_index',
        /** @infection-ignore-all */
        methods: ['GET']
    )]
    public function index(CoatRepository $coatRepository): Response
    {
        return $this->render('clothing/coat/index.html.twig', [
            'coats' => $coatRepository->findAll(),
        ]);
    }
}
