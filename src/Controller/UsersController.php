<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users_home")
     */
    public function index()
    {
        return $this->render('users/homeUsers.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}
