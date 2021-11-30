<?php

// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
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
    * @return Response A response instance

     */
    public function showAllCategories (string $categoryName, $limit=3): Response
    {
        $checkCategory = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['name' => $categoryName]);

        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findBy(
            ['category' => $checkCategory[0]->getId()])
            ;

    if ($checkCategory == 0 ) {
        throw $this->createNotFoundException(
            'No program with category name : '.$categoryName.' found in categories\'s table.'
        );
        }

        return $this->render('category/show.html.twig', [
            'categoryName' => $categoryName, 'program' => $program,
         ]);
    }

}