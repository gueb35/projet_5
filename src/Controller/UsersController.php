<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Members;

class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users_home")
     */
    public function homeUsers()
    {
        return $this->render('users/homeUsers.html.twig');
    }
    /**
     * @Route("/presentation", name="users_presentation")
     */
    public function presentation()
    {
        return $this->render('users/presentationUsers.html.twig');
    }
    /**
     * @Route("/inscription", name="users_inscription")
     */
    public function inscription()
    {
        $member = new Members();

        $form = $this->createFormBuilder($member)
                      ->add('name')
                      ->add('surname')
                      ->add('email')
                      ->add('town')
                      ->add('pseudo')
                      ->add('password')
                      ->getForm();

        return $this->render('users/inscriptionUsers.html.twig', [
            'formOne' => $form->createView()
        ]);
    }
    /**
     * @Route("/members_access", name="users_members")
     */
    public function membersAccess()
    {
        return $this->render('users/membersUsers.html.twig');
    }
    /**
     * @Route("/administrator", name="users_administrator")
     */
    public function administratorAccess()
    {
        return $this->render('users/administratorUsers.html.twig');
    }
}
