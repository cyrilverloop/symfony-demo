<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The controller for the product CRUD.
 */
#[Route('/product')]
class ProductController extends AbstractController
{
    // Methods :

    /**
     * Displays the product list.
     * @param \App\Repository\ProductRepository $productRepository the product repository.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/',
        name: 'product_index',
        /** @infection-ignore-all */
        methods: ['GET']
    )]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * Displays the form to create a new product.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/new',
        name: 'product_new',
        /** @infection-ignore-all */
        methods: ['GET', 'POST']
    )]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a product informations.
     * @param \App\Entity\Product $product the product.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}',
        name: 'product_show',
        /** @infection-ignore-all */
        methods: ['GET']
    )]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Displays the form to update a product.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Product $product the product.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}/edit',
        name: 'product_edit',
        /** @infection-ignore-all */
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a product.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Product $product the product.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/{id}',
        name: 'product_delete',
        /** @infection-ignore-all */
        methods: ['POST']
    )]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . (int)$product->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
