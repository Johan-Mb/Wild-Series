<?php

// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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