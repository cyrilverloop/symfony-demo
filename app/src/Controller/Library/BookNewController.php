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
 * The controller to add a new book.
 *
 * @psalm-api
 */
final class BookNewController extends AbstractController
{
    // Methods :

    /**
     * Displays the form to create a new book.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/books/new',
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
}
