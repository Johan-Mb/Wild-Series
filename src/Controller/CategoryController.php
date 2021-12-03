<?php

// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Form\CategoryType;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/category", name="category_")
 */

Class CategoryController extends AbstractController
{
    /**
         * @Route("/", name="index")
         * @return Response A response instance
         */
        public function index(): Response
        {
            $categories = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();

            return $this->render('category/index.html.twig', [
                'categories' => $categories
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
            $category = new Category();
            // Create the associated Form
            $form = $this->createForm(CategoryType::class, $category);
            // Get data from HTTP request
            $form->handleRequest($request);
            // Was the form submitted ?
            if ($form->isSubmitted() && $form->isValid())
            {
                // Deal with the submitted data
                // Get the Entity Manager
                $entityManager = $this->getDoctrine()->getManager();
                // Persist Category Object
                $entityManager->persist($category);
                // Flush the persisted object
                $entityManager->flush();
                // Finally redirect to categories list
                return $this->redirectToRoute('category_index');
            }
            // Render the form
            return $this->render('category/new.html.twig', ["form" => $form->createView()]);
        }

    /**
     * @Route("/{categoryName}", methods={"GET"}, requirements={"categoryName"="[^/]+"}, name="browse")
     */

    public function showProgramInCategories (string $categoryName): Response
    {
        $checkCategory = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['name' => $categoryName]);

    if (!$checkCategory) {
        throw $this->createNotFoundException(
            'Désolé mon petit lapin, aucun programme de disponible dans la catégorie '.$categoryName.'.'
        );
        }

        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findBy(
            ['category' => $checkCategory[0]->getId()])
            ;

        return $this->render('category/show.html.twig', [
            'categoryName' => $categoryName, 'program' => $program,
         ]);
    }

}