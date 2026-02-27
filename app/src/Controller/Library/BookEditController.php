<?php

declare(strict_types=1);

namespace App\Controller\Library;

use App\Document\Library\Book;
use App\Form\Library\BookType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to edit a book.
 *
 * @psalm-api
 */
final class BookEditController extends AbstractController
{
    // Methods :

    /**
     * Displays the form to update a book.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Document\Library\Book $book the book.
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/books/{id}/edit',
        name: 'library_book_edit',
        /** @infection-ignore-all */
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Book $book, DocumentManager $documentManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->flush();

            return $this->redirectToRoute('library_book_index');
        }

        return $this->render('library/book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }
}
