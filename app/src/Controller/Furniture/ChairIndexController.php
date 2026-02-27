<?php

declare(strict_types=1);

namespace App\Controller\Furniture;

use App\Repository\Furniture\ChairRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to show the chair list.
 *
 * @psalm-api
 */
final class ChairIndexController extends AbstractController
{
    // Methods :

    /**
     * Displays the chair list.
     * @param \App\Repository\Furniture\ChairRepository $chairRepository the chair repository.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/chairs/',
        name: 'furniture_chair_index',
        /** @infection-ignore-all */
        methods: ['GET']
    )]
    public function index(ChairRepository $chairRepository): Response
    {
        return $this->render('furniture/chair/index.html.twig', [
            'chairs' => $chairRepository->findAll(),
        ]);
    }
}
