<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Entity\Members;


class VisitorController extends AbstractController
{
    /**
     * @Route("/", name="visitor_home")
     */
    public function homeUsers()
    {
        return $this->render('visitors/homeVisitor.html.twig');
    }

    /**
     * @Route("/presentation", name="visitor_presentation")
     */
    public function presentation()
    {
        return $this->render('visitors/presentationVisitor.html.twig');
    }
}
