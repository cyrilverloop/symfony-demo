<?php

declare(strict_types=1);

namespace App\Controller\Clothing;

use App\Entity\Clothing\Coat;
use App\Form\Clothing\CoatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to edit a coat.
 *
 * @psalm-api
 */
final class CoatEditController extends AbstractController
{
    // Methods :

    /**
     * Displays the form to update a coat.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Clothing\Coat $coat the coat.
     * @param \Doctrine\ORM\EntityManagerInterface $clothingEntityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/coats/{id}/edit',
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
}
