<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\ProdOfWeekRepository;
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
    public function createListProd(ProdOfWeekRepository $repo, Request $request, ObjectManager $manager)
    {
        $ProdOfWeeks = new ProdOfWeek();
/********************************** formbyunity**************************************/
        $prod1 = $repo->findOneBy(['id' => '1']);//permet de récupérer les infos du produit identifié par l'id
        $prod2 = $repo->findOneBy(['id' => '2']);//permet de récupérer les infos du produit identifié par l'id
        $prod3 = $repo->findOneBy(['id' => '3']);//permet de récupérer les infos du produit identifié par l'id
        $prod4 = $repo->findOneBy(['id' => '4']);//permet de récupérer les infos du produit identifié par l'id
        $prod5 = $repo->findOneBy(['id' => '5']);//permet de récupérer les infos du produit identifié par l'id
        // dump($prod1);
        $formProdOfWeekUnity1 = $this->createFormBuilder($ProdOfWeeks)
            ->add('prodByUnity',TextType::class)
            ->add('quantityProdUnity',IntegerType::class)
            ->getForm();
        $formProdOfWeekUnity2 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByUnity',TextType::class)
        ->add('quantityProdUnity',IntegerType::class)
        ->getForm();
        $formProdOfWeekUnity3 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByUnity',TextType::class)
        ->add('quantityProdUnity',IntegerType::class)
        ->getForm();
        $formProdOfWeekUnity4 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByUnity',TextType::class)
        ->add('quantityProdUnity',IntegerType::class)
        ->getForm();
        $formProdOfWeekUnity5 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByUnity',TextType::class)
        ->add('quantityProdUnity',IntegerType::class)
        ->getForm();
        $formProdOfWeekUnity1->handleRequest($request);
        $formProdOfWeekUnity2->handleRequest($request);
        $formProdOfWeekUnity3->handleRequest($request);
        $formProdOfWeekUnity4->handleRequest($request);
        $formProdOfWeekUnity5->handleRequest($request);

        if($formProdOfWeekUnity1->isSubmitted() && $formProdOfWeekUnity1->isValid()) {

            $manager->persist($ProdOfWeeks);
            $manager->flush();

            // return $this->redirectToRoute('product_of_the_week');
        }
        if($formProdOfWeekUnity2->isSubmitted() && $formProdOfWeekUnity2->isValid()) {

            $manager->persist($ProdOfWeeks);
            $manager->flush();

            // return $this->redirectToRoute('product_of_the_week');
        }
        if($formProdOfWeekUnity3->isSubmitted() && $formProdOfWeekUnity2->isValid()) {

            $manager->persist($ProdOfWeeks);
            $manager->flush();

            // return $this->redirectToRoute('product_of_the_week');
        }
        if($formProdOfWeekUnity4->isSubmitted() && $formProdOfWeekUnity2->isValid()) {

            $manager->persist($ProdOfWeeks);
            $manager->flush();

            // return $this->redirectToRoute('product_of_the_week');
        }
        if($formProdOfWeekUnity5->isSubmitted() && $formProdOfWeekUnity2->isValid()) {

            $manager->persist($ProdOfWeeks);
            $manager->flush();

            // return $this->redirectToRoute('product_of_the_week');
        }
/********************************** formbykg**************************************/
        $formProdOfWeekByKg1 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByKg',TextType::class)
        ->add('quantityProdKg',IntegerType::class)
        ->getForm();
        $formProdOfWeekByKg2 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByKg',TextType::class)
        ->add('quantityProdKg',IntegerType::class)
        ->getForm();
        $formProdOfWeekByKg3 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByKg',TextType::class)
        ->add('quantityProdKg',IntegerType::class)
        ->getForm();
        $formProdOfWeekByKg4 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByKg',TextType::class)
        ->add('quantityProdKg',IntegerType::class)
        ->getForm();
        $formProdOfWeekByKg5 = $this->createFormBuilder($ProdOfWeeks)
        ->add('prodByKg',TextType::class)
        ->add('quantityProdKg',IntegerType::class)
        ->getForm();

    $formProdOfWeekByKg1->handleRequest($request);
    $formProdOfWeekByKg2->handleRequest($request);
    $formProdOfWeekByKg3->handleRequest($request);
    $formProdOfWeekByKg4->handleRequest($request);
    $formProdOfWeekByKg5->handleRequest($request);

    if($formProdOfWeekByKg1->isSubmitted() && $formProdOfWeekByKg1->isValid()) {

        $manager->persist($ProdOfWeeks);
        $manager->flush();

        // return $this->redirectToRoute('product_of_the_week');
    }
    if($formProdOfWeekByKg2->isSubmitted() && $formProdOfWeekByKg->isValid()) {

        $manager->persist($ProdOfWeeks);
        $manager->flush();

        // return $this->redirectToRoute('product_of_the_week');
    }
    if($formProdOfWeekByKg3->isSubmitted() && $formProdOfWeekByKg->isValid()) {

        $manager->persist($ProdOfWeeks);
        $manager->flush();

        // return $this->redirectToRoute('product_of_the_week');
    }
    if($formProdOfWeekByKg4->isSubmitted() && $formProdOfWeekByKg->isValid()) {

        $manager->persist($ProdOfWeeks);
        $manager->flush();

        // return $this->redirectToRoute('product_of_the_week');
    }
    if($formProdOfWeekByKg5->isSubmitted() && $formProdOfWeekByKg->isValid()) {

        $manager->persist($ProdOfWeeks);
        $manager->flush();

        // return $this->redirectToRoute('product_of_the_week');
    }

        return $this->render('admin/prodWeek.html.twig', [

            'prod1' => $prod1,
            'prod2' => $prod2,
            'prod3' => $prod3,
            'prod4' => $prod4,
            'prod5' => $prod5,
            'formProdOfWeekUnity1' => $formProdOfWeekUnity1->createView(),
            'formProdOfWeekUnity2' => $formProdOfWeekUnity2->createView(),
            'formProdOfWeekUnity3' => $formProdOfWeekUnity3->createView(),
            'formProdOfWeekUnity4' => $formProdOfWeekUnity4->createView(),
            'formProdOfWeekUnity5' => $formProdOfWeekUnity5->createView(),

            'formProdOfWeekByKg1' => $formProdOfWeekByKg1->createView(),
            'formProdOfWeekByKg2' => $formProdOfWeekByKg2->createView(),
            'formProdOfWeekByKg3' => $formProdOfWeekByKg3->createView(),
            'formProdOfWeekByKg4' => $formProdOfWeekByKg4->createView(),
            'formProdOfWeekByKg5' => $formProdOfWeekByKg5->createView()
        ]);
    }

    /**
     * @Route("/membersList", name="members_list")
     */
    public function membersList()
    {
        $repo = $this->getDoctrine()->getRepository(Members::class);
        $membersComp = $repo->findBy(
            array('baskettype' => 'composés')
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
