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
    // public function __construct()
    // {
    //     $this->session = new Session();
    //     dump($this->session->has('adminId'));//retourne false
    //     $attrExist = $this->session->has('adminId');
    //     dump($attrExist);//retourne false
    //     if($attrExist == false){
    //         return $this->redirectToRoute('users_inscription');
    //     }else{
    //         $this->adminId = $this->session->get('adminId');
    //     }
    //     dump($this->adminId);
    // }


    /**
     * fonction qui affiche la page d'accueil de la partie admin
     * 
     * @Route("/admin", name="home_admin")
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
     * @param object $ProdOfWeek2
     * objet représentant les produits de la semaine
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du produit
     * 
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
     * fonction pour définir les produits de la semaine
     * 
     * @param object $ProdOfWeek
     * objet représentant les produits de la semaine
     * @param object $ProdOfWeek2
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
     * @Route("/prodOfWeek/new", name="product_of_the_weeks")
     */
    public function formProdOfWeek(ProdOfWeek $ProdOfWeek =null, ProdOfWeek $ProdOfWeek2 =null, ProdOfWeekRepository $repo, Request $request, ObjectManager $manager, $id)
    {
        $prodsOfWeek = $repo->findAll('nameProd');//permet de récupérer tous les produits du champ nameProd
/********************************** formbyunity**************************************/
        if(!$ProdOfWeek){//si le produit n'existe pas
            $ProdOfWeek = new ProdOfWeek();//crée un nouvel objet
        }
        
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
        return $this->render('admin/prodWeek.html.twig', [

            'id' => $id,
            'formProdOfWeek' => $formProdOfWeek->createView(),
            'prodsOfWeek' => $prodsOfWeek,
        ]);
    }

    /**
     * fonction qui initialise le nombre de panier collectés restant dû(validation du compte)
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du membre
     * 
     * @Route("/initialize/{id}", name="initialize_number_basket_rest")
     */
    public function initializeNumberBasketRest(MembersRepository $repoM, ObjectManager $manager, $id)
    {
        $memberId = $repoM->find($id);
        $newQuantityBasketRest = $memberId->getNumberBasketRest();//récupère le nombre de panier
        $newQuantity = $memberId->setNumberBasketRest('44');//permet de déduire la quantité de produits ds la table des produits de la semaine
        $manager->persist($newQuantity);
        $manager->flush();

        return $this->redirectToRoute('members_list');
    }

    /**
     * fonction qui affiche la liste des membres
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * 
     * @Route("/membersList", name="members_list")
     */
    public function membersList(MembersRepository $repoM)
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
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * 
     * @Route("/deleteAllBask", name="delete_all_bask")
     */
    public function deleteAllBask(MembersRepository $repoM, ObjectManager $manager, ProdBaskCompRepository $repoC)
    {
        //re-définit le nombre de paniers restants à 0 pour tous les membres
        $definedAllBaskRest = $repoM->findBy(
            array('basketType' => 'composés')
        );
        foreach($definedAllBaskRest as $newQuantityBAskRest){
            $newQuantity = $newQuantityBAskRest->setNumberBasketRest('0');
        }

        //permet de vider la table des produits du panier composés lors de la fermeture du lieu de vente
        $prodBask = $repoC->findAll();

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
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param int $id
     * identifiant du membre
     * 
     * @Route("/deleteBaskMember/{id}", name="delete_bask_member")
     */
    public function deleteBaskMember(MembersRepository $repoM, ObjectManager $manager, ProdBaskCompRepository $repoC, $id)
    {
        /*re-définit le nombre de paniers restant pour un membre*/
        $member = $repoM->find($id);
        $newQuantity = $member->setNumberBasketRest('0');//permet de déduire la quantité de produits ds la table des produits de la semaine
        $manager->persist($newQuantity);
        $manager->flush();

        /*supprime le panier correspondant au membre*/
        $prodsBAskCompOfMember = $repoC->findBy(
            array('member_id' => $id)
        );
        foreach($prodsBAskCompOfMember as $delete){
            $manager->remove($delete);
            $manager->flush();
        }
        return $this->redirectToRoute('basket_compouned_list');
    }

    /**
     * fonction qui affiche la liste des membres du panier composés
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * 
     * @Route("/basketComp", name="basket_compouned_list")
     */
    public function showBaskCompList(MembersRepository $repoM, ProdBaskCompRepository $repoC)
    {
        $baskComps = $repoM->findBy(//récupère toutes les entrées
            array('basketType' => 'composés')//correspondant à "composés" ds le champ "basketType"
        );
        foreach($baskComps as $memberId){
            $membersId = $memberId->getId();//récupère l'identifiant en référence avec l'id du membre
        }
        
        $prodBaskMember = $repoC->findAll();
        
        return $this->render('admin/basketComp.html.twig', [
            'baskComps' => $baskComps,//renvoie les infos des membres ds un tableau bouclé à l'affichage 
            'prodBaskMember' => $prodBaskMember

        ]);
    }

    /**
     * fonction qui définit le nombre de paniers composés restant dû
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du membre
     * 
     *@Route("/numberBasketRest/{id}", name="defined_number_basket_rest") 
     */
    public function definedNumberBasketRest(MembersRepository $repoM, ProdBaskCompRepository $repoC, ObjectManager $manager, $id)
    {
        $memberId = $repoM->find($id);
        $newQuantityBasketRest = $memberId->getNumberBasketRest();//récupère le nombre de panier
        $newQuantity = $memberId->setNumberBasketRest($newQuantityBasketRest + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
        $manager->persist($newQuantity);
        $manager->flush();

        return $this->redirectToRoute('basket_collected');
    }
    
    /**
     * fonction qui affiche la liste des membres du panier collectés
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param int $id
     * identifiant du membre
     * 
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
