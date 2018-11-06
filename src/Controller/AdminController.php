<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Members;
use App\Entity\ProdOfWeek;

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
    public function createListProd(Request $request, ObjectManager $manager)
    {
        $ProdOfWeeks = new ProdOfWeek();

        $formProdOfWeekUnity = $this->createFormBuilder($ProdOfWeeks)
            ->add('prodByUnity')
            ->add('quantityProdUnity')
            ->getForm();

        $formProdOfWeekUnity->handleRequest($request);

        if($formProdOfWeekUnity->isSubmitted() && $formProdOfWeekUnity->isValid()) {

            $manager->persist($ProdOfWeeks);
            $manager->flush();

            return $this->redirectToRoute('product_of_the_week');
        }

        $formProdOfWeekByKg = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByKg')
        ->add('quantityProdKg')
        ->getForm();

    $formProdOfWeekByKg->handleRequest($request);

    if($formProdOfWeekByKg->isSubmitted() && $formProdOfWeekByKg->isValid()) {

        $manager->persist($ProdOfWeeks);
        $manager->flush();

        return $this->redirectToRoute('product_of_the_week');
    }

        return $this->render('admin/prodWeek.html.twig', [

            'formProdOfWeekUnity1' => $formProdOfWeekUnity->createView(),
            'formProdOfWeekUnity2' => $formProdOfWeekUnity->createView(),
            'formProdOfWeekUnity3' => $formProdOfWeekUnity->createView(),
            'formProdOfWeekUnity4' => $formProdOfWeekUnity->createView(),
            'formProdOfWeekUnity5' => $formProdOfWeekUnity->createView(),

            'formProdOfWeekByKg6' => $formProdOfWeekByKg->createView(),
            'formProdOfWeekByKg7' => $formProdOfWeekByKg->createView(),
            'formProdOfWeekByKg8' => $formProdOfWeekByKg->createView(),
            'formProdOfWeekByKg9' => $formProdOfWeekByKg->createView(),
            'formProdOfWeekByKg10' => $formProdOfWeekByKg->createView()
        ]);
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
