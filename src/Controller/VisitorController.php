<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VisitorController extends AbstractController
{
    /**
     * @Route("/", name="visitor_home")
     */
    public function homeVisitor()
    {
        return $this->render('visitors/homeVisitor.html.twig');
    }

    /**
     * @Route("/presentation", name="visitor_presentation")
     */
    public function presentationVisitor()
    {
        return $this->render('visitors/presentationVisitor.html.twig');
    }
}
