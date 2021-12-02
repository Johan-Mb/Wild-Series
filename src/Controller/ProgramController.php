<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\ProgramType;

use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/program", name="program_")
 */

Class ProgramController extends AbstractController
{
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

 /**
         * The controller for the category add form
         *
         * @Route("/new", name="new")
         */
        public function new(Request $request) : Response
        {
            // Create a new Category Object
            $program = new Program();
            // Create the associated Form
            $form = $this->createForm(ProgramType::class, $program);
            // Get data from HTTP request
            $form->handleRequest($request);
            // Was the form submitted ?
            if ($form->isSubmitted()) {
                // Deal with the submitted data
                // Get the Entity Manager
                $entityManager = $this->getDoctrine()->getManager();
                // Persist Category Object
                $entityManager->persist($program);
                // Flush the persisted object
                $entityManager->flush();
                // Finally redirect to categories list
                return $this->redirectToRoute('program_index');
            }
            // Render the form
            return $this->render('program/new.html.twig', ["form" => $form->createView()]);
        }


    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="show")
     */
    public function show(Program $program_id): Response
    {

        if (!$program_id) {
            throw $this->createNotFoundException(
                'No program with id : '. $program_id .' found in program\'s table.'
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