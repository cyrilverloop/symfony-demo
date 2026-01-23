<?php

declare(strict_types=1);

namespace App\Controller\Furniture;

use App\Entity\Furniture\Chair;
use App\Form\Furniture\ChairType;
use App\Repository\Furniture\ChairRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller for the chair CRUD.
 *
 * @psalm-api
 */
#[Route('/chairs')]
final class ChairController extends AbstractController
{
    // Methods :

    /**
     * Displays the chair list.
     * @param \App\Repository\Furniture\ChairRepository $chairRepository the chair repository.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/',
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

    /**
     * Displays the form to create a new chair.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/new',
        name: 'furniture_chair_new',
        /** @infection-ignore-all */
        methods: ['GET', 'POST']
    )]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chair = new Chair();
        $form = $this->createForm(ChairType::class, $chair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chair);
            $entityManager->flush();

            return $this->redirectToRoute('furniture_chair_index');
        }

        return $this->render('furniture/chair/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a chair informations.
     * @param \App\Entity\Furniture\Chair $chair the chair.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}',
        name: 'furniture_chair_show',
        /** @infection-ignore-all */
        methods: ['GET']
    )]
    public function show(Chair $chair): Response
    {
        return $this->render('furniture/chair/show.html.twig', [
            'chair' => $chair,
        ]);
    }

    /**
     * Displays the form to update a chair.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Furniture\Chair $chair the chair.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}/edit',
        name: 'furniture_chair_edit',
        /** @infection-ignore-all */
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Chair $chair, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChairType::class, $chair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('furniture_chair_index');
        }

        return $this->render('furniture/chair/edit.html.twig', [
            'chair' => $chair,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a chair.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Furniture\Chair $chair the chair.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}',
        name: 'furniture_chair_delete',
        /** @infection-ignore-all */
        methods: ['POST']
    )]
    public function delete(Request $request, Chair $chair, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . (int)$chair->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($chair);
            $entityManager->flush();
        }

        return $this->redirectToRoute('furniture_chair_index');
    }
}
