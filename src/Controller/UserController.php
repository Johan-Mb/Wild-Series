<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'user')]
    public function index(): Response
    {

        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

        return $this->render('user/index.html.twig', [
            'user' => $users,
        ]);
    }
}
