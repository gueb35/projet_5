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

    public function __construct(){
        $this->session = new Session();
        $this->memberId = $this->session->get('memberId');
    }

    /**
     * @Route("/members", name="my_compte")
     */
    public function showListProdOfWeek(MembersRepository $repoM)
    {
        $memberCount = $repoM->find($this->memberId);//permet de récupérer les infos du membre identifier par l'id
        return $this->render('members/accountMembers.html.twig', [
            'id' => $this->memberId,
            'memberCount' => $memberCount
        ]);
    }

    /**
     * fonction permmettant de valider le panier en initialisant le nombre de panier restant à 1
     * 
     * @Route ("/validateBask", name="validate_bask")
     */
    public function validateBask(ProdOfWeekRepository $repo, MembersRepository $repoM, ObjectManager $manager)
    {
        $checkDispoProdU = $repo->findBy(array('quantityProdUnity' => '0'));
        foreach($checkDispoProdU as $noDispoU){
            $manager->remove($noDispoU); 
            $manager->flush(); 
        }
        $checkDispoProdK = $repo->findBy(array('quantityProdKg' => '0'));
        foreach($checkDispoProdU as $noDispoK){
            $manager->remove($noDispoK);  
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
     *@Route("/deleteProdBaskComp/{id}/{name}", name="delete_prod_bask_comp") 
     */
    public function deleteProdBaskComp(ProdBaskCompRepository $repoC, ProdOfWeekRepository $repo, ObjectManager $manager, $name, $id=null)
    {
        $prodNameUnity = $repo->findBy(array('prodByUnity' => $name));
        foreach($prodNameUnity as $quantityU){
            $newQuantityProdUnity = $quantityU->getQuantityProdUnity();//récupère le nombre de produit
        }
        $prodNameKg = $repo->findBy(array('prodByKg' => $name));
        foreach($prodNameKg as $quantity){
            $newQuantityProdKg = $quantity->getQuantityProdKg();//récupère le nombre de produit
        }

        $prodsOfMember = $repoC->find($id);
        $quantityProdOfMember = $prodsOfMember->getQuantityProd();


        if($prodNameUnity == null){
            $newQuantity = $quantity->setQuantityProdKg($newQuantityProdKg + $quantityProdOfMember);
        }else{
            $newQuantity = $quantityU->setQuantityProdUnity($newQuantityProdUnity + $quantityProdOfMember);
        }
        $manager->persist($newQuantity);
        $manager->flush();

        $prodToDelete = $repoC->find($id);
        $memberId = $prodToDelete->getMemberId();
        $manager->remove($prodToDelete);
        $manager->flush();

        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * @Route("/basket_compouned", name="basket_compouned")//appel via le lien du menu
     * @Route("/basket_compouned/{name}", name="basket_comp")//appel lors de la compositon du panier
     */
    public function basket_compouned(MembersRepository $repoM, ProdBaskComp $ProdBaskComp = null, ProdOfWeekRepository $repo, ProdBaskCompRepository $repoC, ObjectManager $manager, $name = null)
    {
        $memberCount = $repoM->find($this->memberId);//permet de récupérer les infos du membre identifier par l'id pour afficher un bonjour perso

        $memberId = $this->memberId;
        $nameProd = $name;

        /*récupère l'entrée corresp au nom du produit $nameProd*/
        $prodUnityExist = $repo->findBy(//récupère ds le champ prodByUnity de la table des produits celui
            // dont le nom correspond au nom du produit $nameProd
            array('prodByUnity' => $nameProd)//afin de vérifier si c'est un produit au kilo ou à l'unité
        );
        foreach($prodUnityExist as $quantityU){
            $newQuantityProdUnity = $quantityU->getQuantityProdUnity();//récupère le nombre de produit
            $idProdUnity = $quantityU->getId();//récupère l'identifiant
        }

        /*récupère l'entrée corresp au nom du produit $nameProd*/
        $prodKgExist = $repo->findBy(
            array('prodByKg' => $nameProd)
        );
        foreach($prodKgExist as $quantity){
            $newQuantityProdKg = $quantity->getQuantityProdKg();//récupère le nombre de produit
            $idProdKg = $quantity->getId();//récupère l'identifiant
        }

        //vérifie si le produit insérer ds la panier est au poids ou à l'unité
        if($prodUnityExist == null){//vérifie si ce produit n'existe pas(est null), si il n'existe pas c'est que c'est un produit au kilo
            $unityOrKg = 'kg';//définit le mode de vente
            if($newQuantityProdKg == '0'){
                $nameProd = null;
            }else{
                $newQuantity = $quantity->setQuantityProdKg($newQuantityProdKg + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
                $manager->persist($newQuantity);
                $manager->flush();
            }
        }else{//donc produit à l'unité !!
            $unityOrKg = 'unité';//définit le mode de vente
            if($newQuantityProdUnity == '0'){
                $nameProd = null;
            }else{
                $newQuantity = $quantityU->setQuantityProdUnity($newQuantityProdUnity + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
                $manager->persist($newQuantity);
                $manager->flush();
            }
        }
        /***/

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
            $ProdBaskComp->setKgOrUntity($unityOrKg);
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

        $prodByUnity = $repo->prodByUnity();//permet de récupérer tous les produits du champ proByUnity pour afficher la liste
        $prodByKg = $repo->prodByKg();//permet de récupérer tous les produits du champ proByKg

        return $this->render('members/basketCompounedMembers.html.twig', [
            'id' => $this->memberId,
            'basketMember' => $basketMember,//en lien avec la ligne 49
            'prodByUnity' => $prodByUnity,//en lien à la ligne 38
            'prodByKg' => $prodByKg,//en lien à la ligne 39
            'memberCount' => $memberCount
        ]);
    }
}