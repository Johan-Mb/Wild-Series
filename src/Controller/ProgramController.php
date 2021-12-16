<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;

use App\Repository\ProgramRepository;
use App\Service\Slugify;
use App\Form\CommentType;
use App\Form\ProgramType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/program", name="program_")
 */

Class ProgramController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
             ->getRepository(Program::class)
             ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs
         ]);
    }


    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     * @return Response
     */
    public function newProgram(Request $request, EntityManagerInterface $entityManager, Slugify $slugger, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($program);

            // Add Slugify
            $slug = $slugger->generate($program->getTitle());
            $program->setSlug($slug);

            // Set the program's owner
            $program->setOwner($this->getUser());
            $entityManager->flush();

            $email = (new Email())
            ->from('johan@wilder.com')
            ->to('johan.mabit@gmail.com')
            ->subject('Une nouvelle série vient d\'être publiée !')
            ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="edit", methods={"GET", "POST"})
     * @return Response
     */
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if (!($this->getUser() == $program->getOwner())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     * @return Response
     */
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="show")
     */
    public function show(Program $programs, Slugify $slugger): Response
    {
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program with id : '. $programs->getSlug() .' found in program\'s table.'
            );
            }

                return $this->render('program/show.html.twig', [
                    'program' => $programs,
                    'slug' => $slugger,
                ]);
    }

    /**
     * @Route("/{slug}/season/{season_id}", methods={"GET"}, requirements={"id"="\d+"}, name="showSeason")
     */
    public function showSeason (Program $program_id, Season $season_id): Response
    {
        if (!$season_id) {
            throw $this->createNotFoundException(
                'No episode with id : '. $season_id->getId() .' found.'
            );
            }
                return $this->render('program/season.html.twig', [
                    'season' => $season_id,
                    'program' => $program_id,
                ]);
    }

    /**
    * @Route("/{program}/season/{season}/episode/{episode}", methods={"GET", "POST"}, name="showEpisode")
    * @ParamConverter("program", class="App\Entity\Program",  options={"mapping": {"program": "slug"}})
    * @ParamConverter("episode", class="App\Entity\Episode",  options={"mapping": {"episode": "slug"}})
    */

    public function showEpisode(Request $request, Program $program, Season $season, Episode $episode, EntityManagerInterface $entityManager,): Response
    {

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUserId($this->getUser());
            $comment->setEpisodeId($episode);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('program_showEpisode', [], Response::HTTP_SEE_OTHER);

        }

                return $this->render('program/episode.html.twig', [
                    'episode' => $episode,
                    'program' => $program,
                    'season' => $season,
                    'comment_form' => $form->createView(),

                ]);
    }
}