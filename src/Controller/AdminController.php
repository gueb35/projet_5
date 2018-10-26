<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="home_admin")
     */
    public function homeAdmin()
    {
        return $this->render('admin/homeAdmin.html.twig');
    }
    /**
     * @Route("/prodOfWeek", name="product_of_the_week")
     */
    public function createListProd()
    {
        return $this->render('admin/prodWeek.html.twig');
    }
    /**
     * @Route("/membersList", name="members_list")
     */
    public function membersList()
    {
        return $this->render('admin/membersList.html.twig');
    }
    /**
     * @Route("/basketComp", name="basket_compouned_list")
     */
    public function showBaskCompList()
    {
        return $this->render('admin/basketComp.html.twig');
    }
    /**
     * @Route("/basketColl", name="basket_collected")
     */
    public function showBaskCollList()
    {
        return $this->render('admin/basketColl.html.twig');
    }
}
