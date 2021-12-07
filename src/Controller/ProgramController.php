<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;

use App\Service\Slugify;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/program", name="program_")
 */

Class ProgramController extends AbstractController
{

    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @Route("/", name="index")
     * @return Response A response instance
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

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function newProgram(Request $request, EntityManagerInterface $entityManager): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($program);
            // Add Slugify
            // $slug = $slugify->generate($program->getTitle());
            // $program->strtolower(setSlug($this->slugger->slug($program->getTitle())));
            $entityManager->flush();

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
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

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="show")
     */
    public function show(Program $program_id): Response
    {

        if (!$program_id) {
            throw $this->createNotFoundException(
                'No program with id : '. $program_id->getId() .' found in program\'s table.'
            );
            }

                return $this->render('program/show.html.twig', [
                    'program' => $program_id,
                ]);
    }

    /**
     * @Route("/{id}/season/{season_id}", methods={"GET"}, requirements={"id"="\d+"}, name="showSeason")
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
    * @Route("/{id}/season/{season_id}/episode/{episode_id}", methods={"GET"}, requirements={"id"="\d+"}, name="showEpisode")
    */

    public function showEpisode(Program $program_id, Season $season_id, Episode $episode_id): Response
    {
        if (!$episode_id) {
            throw $this->createNotFoundException(
                'No episode with id : '. $sepisode_id->getId() .' found.'
            );
            }

                return $this->render('program/episode.html.twig', [
                    'episode' => $episode_id,
                    'program' => $program_id,
                    'season' => $season_id,
                ]);
    }
}