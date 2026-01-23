<?php

declare(strict_types=1);

namespace App\Controller\Clothing;

use App\Entity\Clothing\Coat;
use App\Form\Clothing\CoatType;
use App\Repository\Clothing\CoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller for the coat CRUD.
 *
 * @psalm-api
 */
#[Route('/coats')]
final class CoatController extends AbstractController
{
    // Methods :

    /**
     * Displays the coat list.
     * @param \App\Repository\Clothing\CoatRepository $coatRepository the coat repository.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/',
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

    /**
     * Displays the form to create a new coat.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \Doctrine\ORM\EntityManagerInterface $clothingEntityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/new',
        name: 'clothing_coat_new',
        /** @infection-ignore-all */
        methods: ['GET', 'POST']
    )]
    public function new(Request $request, EntityManagerInterface $clothingEntityManager): Response
    {
        $coat = new Coat();
        $form = $this->createForm(CoatType::class, $coat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clothingEntityManager->persist($coat);
            $clothingEntityManager->flush();

            return $this->redirectToRoute('clothing_coat_index');
        }

        return $this->render('clothing/coat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a coat informations.
     * @param \App\Entity\Clothing\Coat $coat the coat.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}',
        name: 'clothing_coat_show',
        /** @infection-ignore-all */
        methods: ['GET']
    )]
    public function show(Coat $coat): Response
    {
        return $this->render('clothing/coat/show.html.twig', [
            'coat' => $coat,
        ]);
    }

    /**
     * Displays the form to update a coat.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Clothing\Coat $coat the coat.
     * @param \Doctrine\ORM\EntityManagerInterface $clothingEntityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}/edit',
        name: 'clothing_coat_edit',
        /** @infection-ignore-all */
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Coat $coat, EntityManagerInterface $clothingEntityManager): Response
    {
        $form = $this->createForm(CoatType::class, $coat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clothingEntityManager->flush();

            return $this->redirectToRoute('clothing_coat_index');
        }

        return $this->render('clothing/coat/edit.html.twig', [
            'coat' => $coat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a coat.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Clothing\Coat $coat the coat.
     * @param \Doctrine\ORM\EntityManagerInterface $clothingEntityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}',
        name: 'clothing_coat_delete',
        /** @infection-ignore-all */
        methods: ['POST']
    )]
    public function delete(Request $request, Coat $coat, EntityManagerInterface $clothingEntityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . (int)$coat->getId(), (string)$request->request->get('_token'))) {
            $clothingEntityManager->remove($coat);
            $clothingEntityManager->flush();
        }

        return $this->redirectToRoute('clothing_coat_index');
    }
}
