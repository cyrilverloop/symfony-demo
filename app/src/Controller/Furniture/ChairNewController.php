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
 * The controller to add a new chair.
 *
 * @psalm-api
 */
final class ChairNewController extends AbstractController
{
    // Methods :

    /**
     * Displays the form to create a new chair.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/chairs/new',
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
}
