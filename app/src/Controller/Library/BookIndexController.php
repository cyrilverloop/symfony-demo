<?php

declare(strict_types=1);

namespace App\Controller\Library;

use App\Repository\Library\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to show the book list.
 *
 * @psalm-api
 */
final class BookIndexController extends AbstractController
{
    // Methods :

    /**
     * Displays the book list.
     * @param \App\Repository\Library\BookRepository $bookRepository the book repository.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/books/',
        name: 'library_book_index',
        /** @infection-ignore-all */
        methods: ['GET']
    )]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('library/book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }
}
