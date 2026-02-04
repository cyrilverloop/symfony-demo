<?php

declare(strict_types=1);

namespace App\Controller\Library;

use App\Document\Library\Book;
use App\Form\Library\BookType;
use App\Repository\Library\BookRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller for the book CRUD.
 *
 * @psalm-api
 */
#[Route('/books')]
final class BookController extends AbstractController
{
    // Methods :

    /**
     * Displays the book list.
     * @param \App\Repository\Library\BookRepository $bookRepository the book repository.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/',
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

    /**
     * Displays the form to create a new book.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/new',
        name: 'library_book_new',
        /** @infection-ignore-all */
        methods: ['GET', 'POST']
    )]
    public function new(Request $request, DocumentManager $documentManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->persist($book);
            $documentManager->flush();

            return $this->redirectToRoute('library_book_index');
        }

        return $this->render('library/book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a book informations.
     * @param \App\Document\Library\Book $book the book.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}',
        name: 'library_book_show',
        /** @infection-ignore-all */
        methods: ['GET']
    )]
    public function show(Book $book): Response
    {
        return $this->render('library/book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * Displays the form to update a book.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Document\Library\Book $book the book.
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}/edit',
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

    /**
     * Deletes a book.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Document\Library\Book $book the book.
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}',
        name: 'library_book_delete',
        /** @infection-ignore-all */
        methods: ['POST']
    )]
    public function delete(Request $request, Book $book, DocumentManager $documentManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . (string)$book->getId(), (string)$request->request->get('_token'))) {
            $documentManager->remove($book);
            $documentManager->flush();
        }

        return $this->redirectToRoute('library_book_index');
    }
}
