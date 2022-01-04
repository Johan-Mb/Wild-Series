<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Service\Slugify;
use App\Form\EpisodeType;
use Symfony\Component\Mime\Email;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/episode")
 */

class EpisodeController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="episode_index")
     * @return Response
     */

    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'episode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Slugify $slugger, MailerInterface $mailer): Response
    {
        $program = new Program();
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($episode);

            // Add Slugify
            $slug = $slugger->generate($episode->getTitle());
            $episode->setSlug($slug);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouvel épisode a bien été créé !');

            $email = (new Email())
            ->from('johan@wilder.com')
            ->to('johan.mabit@gmail.com')
            ->subject('Un nouvel épisode vient d\'être publié !')
            ->html($this->renderView('Program/newEpisodeEmail.html.twig', ['episode' => $episode, 'program' => $episode->getSeasonId()->getProgramId()]));

            $mailer->send($email);

            return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/new.html.twig', [
            'episode' => $episode,
            'program' => $program,
            'form' => $form,
        ]);
    }


    #[Route('/{slug}', methods:['GET'], name: 'episode_show')]
    public function show(Episode $episode): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
        ]);
    }



    #[Route('/{slug}/edit', name: 'episode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Episode $episode, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'episode_delete', methods: ['POST'])]
    public function delete(Request $request, Episode $episode, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        $this->addFlash('delete', "L'épisode a bien été supprimé !");

        return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
    }
}
