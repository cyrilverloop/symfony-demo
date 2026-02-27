<?php

declare(strict_types=1);

namespace App\Controller\Furniture;

use App\Entity\Furniture\Chair;
use App\Form\Furniture\ChairType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to edit a chair.
 *
 * @psalm-api
 */
final class ChairEditController extends AbstractController
{
    // Methods :

    /**
     * Displays the form to update a chair.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Furniture\Chair $chair the chair.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/chairs/{id}/edit',
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
}
