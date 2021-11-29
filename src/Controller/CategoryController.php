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
     * @Route("/{CategoryName}", methods={"GET"}, requirements={"CategoryName"="[^/]+"}, name="show")
     */
    public function showAllCategories(string $categoryName): Response
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

    //     /**
    //  * @Route("/{CategoryName}/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="show")
    //  */
    // public function showOneCategory(int $id): Response
    // {
    //     $oneCategory = $this->getDoctrine()
    //     ->getRepository(Category::class)
    //     ->findOneBy(['id' => $id]);

    // if (!$oneCategory) {
    //     throw $this->createNotFoundException(
    //         'No program with category name : '.$id.' found in categories\'s table.'
    //     );
    //     }

    //     return $this->render('category/show.html.twig', [
    //         'oneCategory' => $oneCategory,
    //      ]);

    // }
}