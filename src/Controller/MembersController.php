<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MembersController extends AbstractController
{
        /**
     * @Route("/members", name="my_compte")
     */
    public function my_compte()
    {
        return $this->render('members/accountMembers.html.twig');
    }
        /**
     * @Route("/basket_compouned", name="basket_compouned")
     */
    public function basket_compouned()
    {
        return $this->render('members/basketCompounedMembers.html.twig');
    }
}