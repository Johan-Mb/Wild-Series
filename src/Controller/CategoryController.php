<?php

// src/Controller/CategoryController.php
namespace App\Controller;

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
     * @Route("/category/{CategoryName}", methods={"GET"}, requirements={"id"="\d+"}, name="show")
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['CategoryName' => $categoryName]);

    if (!$category) {
        throw $this->createNotFoundException(
            'No program with category name : '.$categoryName.' found in categories\'s table.'
        );
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
         ]);
    }
}