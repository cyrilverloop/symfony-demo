<?php

declare(strict_types=1);

namespace App\Controller\Library;

use App\Document\Library\Book;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to delete a book.
 *
 * @psalm-api
 */
final class BookDeleteController extends AbstractController
{
    // Methods :

    /**
     * Deletes a book.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Document\Library\Book $book the book.
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/books/{id}',
        name: 'library_book_delete',
        /** @infection-ignore-all */
        methods: ['POST'],
        requirements: ['id' => '\S{24}']
    )]
    public function delete(Request $request, Book $book, DocumentManager $documentManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . (string)$book->id, (string)$request->request->get('_token'))) {
            $documentManager->remove($book);
            $documentManager->flush();
        }

        return $this->redirectToRoute('library_book_index');
    }
}
