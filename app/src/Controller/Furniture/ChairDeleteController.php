<?php

declare(strict_types=1);

namespace App\Controller\Furniture;

use App\Entity\Furniture\Chair;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to delete a chair.
 *
 * @psalm-api
 */
final class ChairDeleteController extends AbstractController
{
    // Methods :

    /**
     * Deletes a chair.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Furniture\Chair $chair the chair.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/chairs/{id}',
        name: 'furniture_chair_delete',
        /** @infection-ignore-all */
        methods: ['POST'],
        requirements: ['id' => '\d+']
    )]
    public function delete(Request $request, Chair $chair, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . (int)$chair->id, (string)$request->request->get('_token'))) {
            $entityManager->remove($chair);
            $entityManager->flush();
        }

        return $this->redirectToRoute('furniture_chair_index');
    }
}
