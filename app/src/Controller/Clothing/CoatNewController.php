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
 * The controller to add a new coat.
 *
 * @psalm-api
 */
final class CoatNewController extends AbstractController
{
    // Methods :

    /**
     * Displays the form to create a new coat.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \Doctrine\ORM\EntityManagerInterface $clothingEntityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/coats/new',
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
}
