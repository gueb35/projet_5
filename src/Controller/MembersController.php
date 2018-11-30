<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\ProdOfWeekRepository;
use App\Repository\ProdBaskCompRepository;
use App\Repository\MembersRepository;
use App\Entity\Members;
use App\Entity\ProdOfWeek;
use App\Entity\ProdBaskComp;
use Symfony\Component\HttpFoundation\Session\Session;

class MembersController extends AbstractController
{

    public function __construct()
    {
        $this->session = new Session();
        // dump($this->session->has('memberId'));//retourne false
        // $attrExist = $this->session->has('memberId');
        // dump($attrExist);//retourne false
        // if($attrExist == false){
        //     dump($attrExist);
        //     return $this->redirectToRoute('users_inscription');
        // }else{
            $this->memberId = $this->session->get('memberId');
        // }


        // $this->session = new Session();
        // dump($this->session->has('memberId'));//retourne false
        // if($this->session->has('memberId') == false){
        //     return $this->redirectToRoute('users_home');
        // }else{
        //     $this->memberId = $this->session->get('memberId');
        // }
        // dump($this->memberId);
    }

    /**
     * fonction permettant de vérifier l'accès aux pages de l'espace membre
     */
    // private function checkAccess()
    // {
    //     return $this->session->has('memberId');
    // }

    /**
     * fonction permettant la déconnexion à l'espace membre puis la re-direction sur la page d'acceuil du site
     * 
     * @Route("/deconnectMember", name="deconnect_member")
     */
    public function deconnectMember()
    {
        $this->session->clear();
        return $this->redirectToRoute('visitor_home');
    }

    /**
     * fonction pour accéder à la page du compte du membre
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * 
     * @Route("/members", name="my_compte")
     */
    public function showListProdOfWeek(MembersRepository $repoM)
    {      
        // if($this->checkAccess() == false){
        //     return $this->redirectToRoute('users_inscription');
        // }
        // dump($this->checkAccess());
        $memberCount = $repoM->find($this->memberId);//permet de récupérer les infos du membre identifier par l'id
        return $this->render('members/accountMembers.html.twig', [
            'id' => $this->memberId,
            'memberCount' => $memberCount
        ]);
    }

    /**
     * fonction permmettant de valider le panier en initialisant le nombre de panier restant à 1
     * 
     * @param repository $repo
     * parameter converter pour parler avec la table prodOfWeek
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * 
     * @Route ("/validateBask", name="validate_bask")
     */
    public function validateBask(ProdOfWeekRepository $repo, MembersRepository $repoM, ObjectManager $manager)
    {
        $checkDispoProd = $repo->findBy(array('quantity' => '0'));
        foreach($checkDispoProd as $noDispo){
            $manager->remove($noDispo); 
            $manager->flush(); 
        }

        $newNumberBaskRest = $repoM->find($this->memberId);
        $newNumberBaskRest = $newNumberBaskRest->setnumberBasketRest('1');
        $manager->persist($newNumberBaskRest);
        $manager->flush();

        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * fonction servant à enlever un produit du panier composé et le rajouter aux produits de la semaine
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param repository $repo
     * parameter converter pour parler avec la table prodOfWeek
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param string $name
     * nom du produit à enlever du panier
     * @param int $id
     * identifiant du produit à enlever du panier
     * 
     *@Route("/deleteProdBaskComp/{id}/{name}", name="delete_prod_bask_comp") 
     */
    public function deleteProdBaskComp(ProdBaskCompRepository $repoC, ProdOfWeekRepository $repo, ObjectManager $manager, $name, $id=null)
    {
        $prodName = $repo->findBy(array('nameProd' => $name));
        foreach($prodName as $quantity){
            $newQuantityProd = $quantity->getQuantity();//récupère le nombre de produit
        }

        $prodsOfMember = $repoC->find($id);//récupère l'entrée de la table correspondant à l'id du produit
        $quantityProdOfMember = $prodsOfMember->getQuantityProd();//récupère la quantité

        $newQuantity = $quantity->setQuantity($newQuantityProd + $quantityProdOfMember);
        $manager->persist($newQuantity);
        $manager->flush();

        $prodToDelete = $repoC->find($id);
        $memberId = $prodToDelete->getMemberId();
        $manager->remove($prodToDelete);
        $manager->flush();

        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * fonction permettant de composer son panier
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param entity $ProdBaskComp
     * parameter converter pour mettre un produit dans le panier
     * @param repository $repo
     * parameter converter pour parler avec la table prodOfWeek
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param string $name
     * nom du produit à enlever du panier
     * 
     * @Route("/basket_compouned", name="basket_compouned")//appel via le lien du menu
     * @Route("/basket_compouned/{name}", name="basket_comp")//appel lors de la compositon du panier
     */
    public function basket_compouned(MembersRepository $repoM, ProdBaskComp $ProdBaskComp = null, ProdOfWeekRepository $repo, ProdBaskCompRepository $repoC, ObjectManager $manager, $name = null)
    {
        // if($this->checkAccess() == false){
        //     return $this->redirectToRoute('users_inscription');
        // }

        $memberCount = $repoM->find($this->memberId);//permet de récupérer les infos du membre identifier par l'id pour afficher un bonjour perso

        $memberId = $this->memberId;
        $nameProd = $name;

        /*récupère l'entrée corresp au nom du produit $nameProd*/
        $prodExist = $repo->findBy(//récupère ds le champ nameProd de la table des produits celui
            // dont le nom correspond au nom du produit $nameProd
            array('nameProd' => $nameProd)//($nameProd) afin de vérifier si c'est un produit au kilo ou à l'unité
        );
        foreach($prodExist as $quantity){
            $newQuantityProd = $quantity->getQuantity();//récupère le nombre de produit
            $saleType = $quantity->getSaleType();
            $idProdUnity = $quantity->getId();//récupère l'identifiant
        }
        //vérifie si le produit insérer ds la panier est au poids ou à l'unité
        if(!empty($prodExist)){//vérifie si ce produit n'existe pas(est null), si il n'existe pas c'est que c'est un produit au kilo
            if($newQuantityProd == '0'){
                $nameProd = null;
            }else{
                $newQuantity = $quantity->setQuantity($newQuantityProd + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
                $manager->persist($newQuantity);
                $manager->flush();
            }
        }

        /*insère un produit ds la table des paniers composés en relation avec l'id du membre*/
        if($nameProd){//si un nom de produit est présent ds l'url
            $prodsOfMember = $repoC->findBy(//récupère tous les prod correspondant à l'id du membre
                array('member_id' => $memberId)
            );
            foreach($prodsOfMember as $memberIdProd){
                $prodExist = $memberIdProd->getNameProd();//récupère les noms de prod ds le tableau
                if($prodExist == $nameProd){//si un nom correspond
                    $idProd = $memberIdProd->getId();//récupère son id
                    $updateProd = $repoC->find($idProd);//récupère l'entrée correspondant à l'id
                    $moreQuantity = $updateProd->getQuantityProd();
                    $updateProd->setQuantityProd($moreQuantity + '1');
        
                    $manager->persist($updateProd);
                    $manager->flush();
                    return $this->redirectToRoute('basket_compouned');
                }
            }

            $ProdBaskComp = new ProdBaskComp();
            $ProdBaskComp->setMemberId($this->memberId);
            $ProdBaskComp->setNameProd($name);
            $ProdBaskComp->setKgOrUntity($saleType);
            $ProdBaskComp->setQuantityProd('1');

            $manager->persist($ProdBaskComp);
            $manager->flush();
        }
        /***/
        
        //va chercher ds le champ member_id de la table des paniers composés
        //toutes les entrées correspondant au numéro du membre
        $basketMember = $repoC->findBy(
            array('member_id' => $memberId)
        );

        $prod = $repo->findAll('nameProd');//permet de récupérer tous les produits du champ nameprod pour afficher la liste

        return $this->render('members/basketCompounedMembers.html.twig', [
            'id' => $this->memberId,
            'basketMember' => $basketMember,//en lien avec la ligne 49
            'prod' => $prod,//en lien à la ligne 38
            'memberCount' => $memberCount
        ]);
    }
}