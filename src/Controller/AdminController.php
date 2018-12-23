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
use App\Repository\MembersOfBasketCompounedRepository;
use App\Repository\MembersOfBasketCollectedRepository;
use App\Entity\MembersOfBasketCompouned;
use App\Entity\MembersOfBasketCollected;
use App\Entity\ProdBaskComp;
use App\Entity\ProdOfWeek;

/**  @Route("/admin") */
class AdminController extends AbstractController
{
    /**
     * fonction qui affiche la page d'accueil de la partie admin
     * 
     * @Route("/", name="home_admin")
     */
    public function homeAdmin()
    {
        return $this->render('admin/homeAdmin.html.twig');
    }

    /**
     * fonction permettant d'actualiser la quantité de produits dans les produits de la semaine
     * 
     * @param object $ProdOfWeek
     * objet représentant les produits de la semaine
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du produit
     * 
     * @Route("/deleteProd/{id}", name="delete_prod")
     */
    public function deleteProduct(ProdOfWeek $ProdOfWeek =null, ObjectManager $manager, $id=null)
    {
        $manager->remove($ProdOfWeek);
        $manager->flush();

        return $this->redirectToRoute('product_of_the_weeks',['id' => $id]);
    }

    /**
     * fonction pour définir les produits de la semaine(partie 1) et afficher la liste des produits de la semaine(partie 2)
     * 
     * @param object $ProdOfWeek
     * objet représentant les produits de la semaine
     * @param repository $repo
     * parameter converter pour parler avec la table prodOfWeek
     * @param object $request
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du produit
     * 
     * @Route("/prodOfWeek/{id}", name="product_of_the_week")
     * @Route("/new", name="product_of_the_weeks")
     */
    public function formProdOfWeek(ProdOfWeek $ProdOfWeek =null, ProdOfWeekRepository $repo, Request $request, ObjectManager $manager, $id=null)
    {
        /**partie 1 : permet d'ajouter un produit dans les produits de la semaine**/
        if(!$ProdOfWeek){//si le produit n'existe pas
            $ProdOfWeek = new ProdOfWeek();//crée un nouvel objet
        }
        
        /*formulaire de création de produit*/
        $formProdOfWeek= $this->createFormBuilder($ProdOfWeek)
            ->add('nameProd',TextType::class)
            ->add('quantity',IntegerType::class)
            ->add('saleType' ,TextType::class)
            ->getForm();

        $formProdOfWeek->handleRequest($request);

        if($formProdOfWeek->isSubmitted() && $formProdOfWeek->isValid()) {
            $manager->persist($ProdOfWeek);
            $manager->flush();

            return $this->redirectToRoute('product_of_the_week', ['id' => $ProdOfWeek->getId()]);
        }
        /*******/

        /**partie 2 : renvoie l'ensemble des produits pour afficher la liste des produits de la semaine**/
        $prodsOfWeek = $repo->findAll('nameProd');//permet de récupérer tous les produits du champ nameProd
        /******/

        return $this->render('admin/prodWeek.html.twig', [

            'id' => $id,
            'formProdOfWeek' => $formProdOfWeek->createView(),
            'prodsOfWeek' => $prodsOfWeek,
        ]);
    }

    /**
     * fonction qui initialise le nombre de panier collectés restant dû(validation du compte)
     * 
     * @param repository $repoMC
     * parameter converter pour parler avec la table membersOfBasketCollected
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du membre
     * 
     * @Route("/initialize/{id}", name="initialize_number_basket_rest")
     */
    public function initializeNumberBasketRest(MembersOfBasketCollectedRepository $repoMC, ObjectManager $manager, $id)
    {
        $memberId = $repoMC->find($id);
        $newQuantity = $memberId->setNumberBasketRest('44');//permet de déduire la quantité de produits ds la table des produits de la semaine
        $manager->persist($newQuantity);
        $manager->flush();

        return $this->redirectToRoute('members_list');
    }


    /**
     * fonction qui affiche une liste de 5 membres
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table membersOfBasketCompouned
     * @param repository $repoMC
     * parameter converter pour parler avec la table membersOfBasketCollected
     * @param int $groupOfMember1
     * groupe de membre du panier composé
     * @param int $groupOfMember2
     * groupe de membre du panier collecté
     * 
     * @Route("/pagination_member_group/{groupOfMember1}/{groupOfMember2}", name="paginationMemberGroup")
     */
    public function paginationMemberGroup(MembersOfBasketCompounedRepository $repoM, MembersOfBasketCollectedRepository $repoMC, $groupOfMember1, $groupOfMember2)
    {
        $groupOfMember1Max = count($repoM->findAll());
        $groupOfMember2Max = count($repoMC->findAll());

        $membersComp = $repoM->findBy(
                array('basketType' => 'composés'),
                array('createdAt' => 'desc'),
                5,
                $groupOfMember1);
    
            $membersColl = $repoMC->findBy(
                array('basketType' => 'collectés'),
                array('createdAt' => 'desc'),
                5,
                $groupOfMember2);

        return $this->render('admin/membersList.html.twig', [
            'groupOfMember1Max' => $groupOfMember1Max,
            'groupOfMember2Max' => $groupOfMember2Max,
            'groupOfMember1' => $groupOfMember1,
            'groupOfMember2' => $groupOfMember2,
            'members1' => $membersComp,
            'members2' => $membersColl
        ]);
    }

    /**
     * fonction qui affiche la liste des 5 premiers membres
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table membersOfBasketCompouned
     * @param repository $repoMC
     * parameter converter pour parler avec la table membersOfBasketCollected
     * 
     * @Route("/membersList", name="members_list")
     */
    public function membersList(MembersOfBasketCompounedRepository $repoM, MembersOfBasketCollectedRepository $repoMC)
    {
        $groupOfMember1Max = count($repoM->findAll());
        $groupOfMember2Max = count($repoMC->findAll());
        dump($groupOfMember1Max);
        dump($groupOfMember2Max);

        $groupOfMember1 = 0;
        $groupOfMember2 = 0;
        dump($groupOfMember1);
        dump($groupOfMember2);

        $membersComp = $repoM->findBy(
            array('basketType' => 'composés'),
            array('createdAt' => 'desc'),
            5,
            $groupOfMember1);
        $membersColl = $repoMC->findBy(
            array('basketType' => 'collectés'),
            array('createdAt' => 'desc'),
            5,
            $groupOfMember2);

        return $this->render('admin/membersList.html.twig', [
            'groupOfMember1Max' => $groupOfMember1Max,
            'groupOfMember2Max' => $groupOfMember2Max,
            'groupOfMember1' => $groupOfMember1,
            'groupOfMember2' => $groupOfMember2,
            'members1' => $membersComp,
            'members2' => $membersColl
        ]);
    }

    /**
     * fonction permettant de vider la table des produits des paniers composés
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table membersOfBasketCompouned
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * 
     * @Route("/deleteAllBask", name="delete_all_bask")
     */
    public function deleteAllBask(MembersOfBasketCompounedRepository $repoM, ObjectManager $manager, ProdBaskCompRepository $repoC)
    {
        //récupère tous les membres ayant adhéré aux paniers composés
        $definedAllBaskRest = $repoM->findAll();
        
        //re-définit le nombre de paniers restants à 0 pour tous les membres du panier composé
        foreach($definedAllBaskRest as $newQuantityBAskRest){
            $newQuantity = $newQuantityBAskRest->setNumberBasketRest('0');
        }

        //récupère tous les paniers composés
        $prodBask = $repoC->findAll();

        //permet de vider la table des produits du panier composés lors de la fermeture du lieu de vente
        foreach($prodBask as $deleteAll){
            $manager->remove($deleteAll);
            $manager->flush();
        }
        return $this->redirectToRoute('basket_compouned_list');
    }

    /**
     * fonction qui efface le panier composés d'un membre après sa remise au membre
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table membersOfBasketCompouned
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param int $memberId
     * identifiant du membre
     * 
     * @Route("/deleteBaskMember/{memberId}", name="delete_bask_member")
     */
    public function deleteBaskMember(MembersOfBasketCompounedRepository $repoM, ObjectManager $manager, ProdBaskCompRepository $repoC, $memberId)
    {
        /*récupère les infos du membre*/
        $member = $repoM->find($memberId);
        //re-définit le nombre de paniers restant pour un membre
        $newQuantity = $member->setNumberBasketRest('0');
        $manager->persist($newQuantity);
        $manager->flush();

        /*récupère les produits du panier correspondant au membre*/
        $prodsBaskCompOfMember = $repoC->findBy(
            array('members' => $memberId)
        );
        /*supprime le panier correspondant au membre*/
        foreach($prodsBaskCompOfMember as $delete){
            $manager->remove($delete);
            $manager->flush();
        }
        return $this->redirectToRoute('basket_compouned_list');
    }

    /**
     * fonction qui affiche la liste des membres du panier composés
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table membersOfBasketCompouned
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * 
     * @Route("/basketComp", name="basket_compouned_list")
     */
    public function showBaskCompList(MembersOfBasketCompounedRepository $repoM, ProdBaskCompRepository $repoC)
    {
        //récupère la liste des membres ayant adhéré aux paniers composés
        $members = $repoM->findAll();
        
        //récupère tous les produits des paniers composés
        $prodBaskMember = $repoC->findAll();
        
        return $this->render('admin/basketComp.html.twig', [
            'members' => $members,//renvoie les infos des membres ds un tableau bouclé à l'affichage 
            'prodBaskMember' => $prodBaskMember

        ]);
    }

    /**
     * fonction qui définit le nombre de paniers collectés restant dû
     * 
     * @param repository $repoMC
     * parameter converter pour parler avec la table membersOfBasketCollected
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du membre
     * 
     *@Route("/definedNumberBasketRest/{id}", name="defined_number_basket_rest") 
     */
    public function definedNumberBasketRest(MembersOfBasketCollectedRepository $repoMC, ObjectManager $manager, $id)
    {
        dump($id);
        $memberId = $repoMC->find($id);
        $newQuantityBasketRest = $memberId->getNumberBasketRest();//récupère le nombre de panier collecté restant dû
        $newQuantity = $memberId->setNumberBasketRest($newQuantityBasketRest + '-1');//permet de déduire la quantité de paniers collecté restant dû
        $manager->persist($newQuantity);
        $manager->flush();

        return $this->redirectToRoute('basket_collected');
    }
    
    /**
     * fonction qui affiche la liste des membres du panier collectés
     * 
     * @param repository $repoMC
     * parameter converter pour parler avec la table membersOfBasketCollected
     * 
     * @Route("/basketColl", name="basket_collected")
     */
    public function showBaskCollList(MembersOfBasketCollectedRepository $repoMC)
    {
        $dayBaskColl1 = $repoMC->findBy(
            array('dayOfWeek' => 'lundi')
        );
        $dayBaskColl2 = $repoMC->findBy(
            array('dayOfWeek' => 'mardi')
        );
        $dayBaskColl3 = $repoMC->findBy(
            array('dayOfWeek' => 'mercredi')
        );
        $dayBaskColl4 = $repoMC->findBy(
            array('dayOfWeek' => 'jeudi')
        );
        $dayBaskColl5 = $repoMC->findBy(
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