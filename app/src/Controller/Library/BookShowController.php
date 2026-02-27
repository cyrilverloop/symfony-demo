<?php

declare(strict_types=1);

namespace App\Controller\Library;

use App\Document\Library\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to show a book.
 *
 * @psalm-api
 */
final class BookShowController extends AbstractController
{
    // Methods :

    /**
     * Displays a book informations.
     * @param \App\Document\Library\Book $book the book.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/books/{id}',
        name: 'library_book_show',
        /** @infection-ignore-all */
        methods: ['GET'],
        requirements: ['id' => '\S{24}']
    )]
    public function show(Book $book): Response
    {
        return $this->render('library/book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
