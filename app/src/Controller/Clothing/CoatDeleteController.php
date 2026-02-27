<?php

declare(strict_types=1);

namespace App\Controller\Clothing;

use App\Entity\Clothing\Coat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The controller to delete a coat.
 *
 * @psalm-api
 */
final class CoatDeleteController extends AbstractController
{
    // Methods :

    /**
     * Deletes a coat.
     * @param \Symfony\Component\HttpFoundation\Request $request the request.
     * @param \App\Entity\Clothing\Coat $coat the coat.
     * @param \Doctrine\ORM\EntityManagerInterface $clothingEntityManager the entity manager.
     * @return \Symfony\Component\HttpFoundation\Response the response.
     */
    #[Route(
        '/coats/{id}',
        name: 'clothing_coat_delete',
        /** @infection-ignore-all */
        methods: ['POST'],
        requirements: ['id' => '\d+']
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
