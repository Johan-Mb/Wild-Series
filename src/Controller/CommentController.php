<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/", name="comment_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("delete/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_showEpisode',
        [
        "program" => $comment->getEpisodeId()->getSeasonId()->getProgramId()->getSlug(),
        "season" => $comment->getEpisodeId()->getSeasonId()->getId(),
        "episode" => $comment->getEpisodeId()->getSlug(),
        ], Response::HTTP_SEE_OTHER);
    }
}