<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\ProdOfWeekRepository;
use App\Repository\ProdBaskCompRepository;
use App\Repository\MembersRepository;
use App\Entity\Members;
use App\Entity\ProdBaskComp;
use App\Entity\ProdOfWeek;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/{id}", name="home_admin")
     */
    public function homeAdmin($id = null)
    {
        return $this->render('admin/homeAdmin.html.twig',[
            'id' => $id
        ]);
    }

    /**
     * @Route("/deleteProd/{id}", name="delete_prod")
     */
    public function deleteProduct(ProdOfWeek $ProdOfWeek =null, ProdOfWeek $ProdOfWeek2 =null, ObjectManager $manager, $id=null)
    {
        $manager->remove($ProdOfWeek);
        $manager->remove($ProdOfWeek2);
        $manager->flush();

        return $this->redirectToRoute('product_of_the_weeks',['id' => $id]);
    }

    /**
     * @Route("/prodOfWeek/{id}", name="product_of_the_week")
     * @Route("/prodOfWeek/new", name="product_of_the_weeks")
     */
    public function formProdOfWeek(ProdOfWeek $ProdOfWeek =null, ProdOfWeek $ProdOfWeek2 =null, ProdOfWeekRepository $repo, Request $request, ObjectManager $manager, $id)
    {
        $prodOfWeekComp = $repo->prodByUnity();//permet de récupérer tous les produits du champ proByUnity
        $prodOfWeekColl = $repo->prodByKg();//permet de récupérer tous les produits du champ proByKg
/********************************** formbyunity**************************************/
        if(!$ProdOfWeek){//si le produit n'existe pas
            $ProdOfWeek = new ProdOfWeek();//crée un nouvel objet
        }
        
        $formProdOfWeekUnity1 = $this->createFormBuilder($ProdOfWeek)
            ->add('prodByUnity',TextType::class)
            ->add('quantityProdUnity',IntegerType::class)
            ->getForm();
        $formProdOfWeekUnity1->handleRequest($request);
        if($formProdOfWeekUnity1->isSubmitted() && $formProdOfWeekUnity1->isValid()) {
            $manager->persist($ProdOfWeek);
            $manager->flush();

            return $this->redirectToRoute('product_of_the_week', ['id' => $ProdOfWeek->getId()]);
        }
/********************************** formbykg**************************************/
        if(!$ProdOfWeek2){//si le produit n'existe pas
            $ProdOfWeek2 = new ProdOfWeek();//crée un nouvel objet
        }
        
        $formProdOfWeekByKg1 = $this->createFormBuilder($ProdOfWeek2)
            ->add('prodByKg',TextType::class)
            ->add('quantityProdKg',IntegerType::class)
            ->getForm();
        $formProdOfWeekByKg1->handleRequest($request);
        if($formProdOfWeekByKg1->isSubmitted() && $formProdOfWeekByKg1->isValid()) {
            $manager->persist($ProdOfWeek2);
            $manager->flush();

            return $this->redirectToRoute('product_of_the_week', ['id' => $ProdOfWeek2->getId()]);
        }
        return $this->render('admin/prodWeek.html.twig', [

            'id' => $id,
            'formProdOfWeekUnity1' => $formProdOfWeekUnity1->createView(),
            'formProdOfWeekByKg1' => $formProdOfWeekByKg1->createView(),
            'prodOfWeekComp' => $prodOfWeekComp,
            'prodOfWeekColl' => $prodOfWeekColl
        ]);
    }

    /**
     * @Route("/initialize/{id}", name="initialize_number_basket_rest")
     */
    public function initializeNumberBasketRest(MembersRepository $repoM, ObjectManager $manager, $id)
    {
        $memberId = $repoM->find($id);
        dump($memberId);
        $newQuantityBasketRest = $memberId->getNumberBasketRest();//récupère le nombre de panier
        dump($newQuantityBasketRest);
        $newQuantity = $memberId->setNumberBasketRest('44');//permet de déduire la quantité de produits ds la table des produits de la semaine
        $manager->persist($newQuantity);
        $manager->flush();

        return $this->redirectToRoute('members_list');
    }

    /**
     * @Route("/membersList", name="members_list")
     */
    public function membersList(MembersRepository $repoM, $id = null)
    {
        $membersComp = $repoM->findBy(
            array('basketType' => 'composés')
        );
        $membersColl = $repoM->findBy(
            array('basketType' => 'collectés')
        );
        return $this->render('admin/membersList.html.twig', [
            'members1' => $membersComp,
            'members2' => $membersColl
        ]);
    }

    /**
     * fonction permettant de vider la table des produits des paniers composés
     * 
     * @Route("/deleteAllBask", name="delete_all_bask")
     */
    public function deleteAllBask(ObjectManager $manager, ProdBaskCompRepository $repoC)
    {
        //permet de vider la table des produits du panier composés lors de la fermeture du lieu de vente
        $prodBask = $repoC->findAll();

        foreach($prodBask as $deleteAll){
            $manager->remove($deleteAll);
            $manager->flush();
        }
        return $this->redirectToRoute('basket_compouned_list');
    }

    /**
     * @Route("/deleteBaskMember/{id}", name="delete_bask_member")
     */
    public function deleteBaskMember(ObjectManager $manager, ProdBaskCompRepository $repoC, $id)
    {
        $prodsBAskCompOfMember = $repoC->findBy(
            array('member_id' => $id)
        );
        dump($prodsBAskCompOfMember);
        foreach($prodsBAskCompOfMember as $delete){
            $manager->remove($delete);
            $manager->flush();
        }
        return $this->redirectToRoute('basket_compouned_list');
    }

    /**
     * @Route("/basketComp", name="basket_compouned_list")
     */
    public function showBaskCompList(MembersRepository $repoM, ProdBaskCompRepository $repoC, $id = null)
    {
        $baskComps = $repoM->findBy(//récupère toutes les entrées
            array('basketType' => 'composés')//correspondant à "composés" ds le champ "basketType"
        );
        foreach($baskComps as $memberId){
            $membersId = $memberId->getId();//récupère l'identifiant en référence avec l'id du membre
        }
        
        $prodBaskMember = $repoC->findAll();
        
        return $this->render('admin/basketComp.html.twig', [
            'id' => $id,
            'baskComps' => $baskComps,//renvoie les infos des membres ds un tableau bouclé à l'affichage 
            'prodBaskMember' => $prodBaskMember

        ]);
    }

    /**
     *@Route("/numberBasketRest/{id}", name="defined_number_basket_rest") 
     */
    public function definedNumberBasketRest(MembersRepository $repoM, ProdBaskCompRepository $repoC, ObjectManager $manager, $id)
    {
        $memberId = $repoM->find($id);
        dump($id);
        dump($memberId);
        $newQuantityBasketRest = $memberId->getNumberBasketRest();//récupère le nombre de panier
        dump($newQuantityBasketRest);
        $newQuantity = $memberId->setNumberBasketRest($newQuantityBasketRest + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
        $manager->persist($newQuantity);
        $manager->flush();

        return $this->redirectToRoute('basket_collected');
    }
    
    /**
     * @Route("/basketColl/{id}", name="basket_collected")
     */
    public function showBaskCollList(MembersRepository $repoM, $id = null)
    {
        $dayBaskColl1 = $repoM->findBy(
            array('dayOfWeek' => 'lundi')
        );
        $dayBaskColl2 = $repoM->findBy(
            array('dayOfWeek' => 'mardi')
        );
        $dayBaskColl3 = $repoM->findBy(
            array('dayOfWeek' => 'mercredi')
        );
        $dayBaskColl4 = $repoM->findBy(
            array('dayOfWeek' => 'jeudi')
        );
        $dayBaskColl5 = $repoM->findBy(
            array('dayOfWeek' => 'vendredi')
        );
        return $this->render('admin/basketColl.html.twig', [
            'id' => $id,
            'day1' => $dayBaskColl1,
            'day2' => $dayBaskColl2,
            'day3' => $dayBaskColl3,
            'day4' => $dayBaskColl4,
            'day5' => $dayBaskColl5

        ]);
    }
}
