<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Members;

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
        $repo = $this->getDoctrine()->getRepository(Members::class);
        $membersComp = $repo->findBy(
            array('baskettype' => 'composés')/*,*/
        );
        $membersColl = $repo->findBy(
            array('baskettype' => 'collectés')
        );
        return $this->render('admin/membersList.html.twig', [
            'members1' => $membersComp,
            'members2' => $membersColl
        ]);
    }
    /**
     * @Route("/basketComp", name="basket_compouned_list")
     */
    public function showBaskCompList()
    {
        $repo = $this->getDoctrine()->getRepository(Members::class);
        $baskComps = $repo->findBy(
            array('baskettype' => 'composés')
        );
        return $this->render('admin/basketComp.html.twig', [
            'baskComps' => $baskComps
        ]);
    }
    /**
     * @Route("/basketColl", name="basket_collected")
     */
    public function showBaskCollList()
    {
        $repo = $this->getDoctrine()->getRepository(Members::class);
        $dayBaskColl1 = $repo->findBy(
            array('dayOfWeek' => 'lundi')
        );
        $dayBaskColl2 = $repo->findBy(
            array('dayOfWeek' => 'mardi')
        );
        $dayBaskColl3 = $repo->findBy(
            array('dayOfWeek' => 'mercredi')
        );
        $dayBaskColl4 = $repo->findBy(
            array('dayOfWeek' => 'jeudi')
        );
        $dayBaskColl5 = $repo->findBy(
            array('dayOfWeek' => 'vendredi')
        );
        return $this->render('admin/basketColl.html.twig', [
            'day1' => $dayBaskColl1,
            'day2' => $dayBaskColl2,
            'day3' => $dayBaskColl3,
            'day4' => $dayBaskColl4,
            'day5' => $dayBaskColl5

        ]);
    }
}
