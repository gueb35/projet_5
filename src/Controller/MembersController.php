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

class MembersController extends AbstractController
{
    /**
     * @Route("/members/{id}", name="my_compte")
     */
    public function showListProdOfWeek(MembersRepository $repoM, $id)
    {
        $memberCount = $repoM->find($id);//permet de récupérer les infos du membre identifier par l'id
        return $this->render('members/accountMembers.html.twig', [
            'id' => $id,
            'memberCount' => $memberCount
        ]);
    }

    /**
     * @Route ("/validateBask/{id}", name="validate_bask")
     */
    public function validateBask(MembersRepository $repoM, ObjectManager $manager, $id)
    {
        $newNumberBaskRest = $repoM->find($id);
        dump($newNumberBaskRest);
        $newNumberBaskRest = $newNumberBaskRest->setnumberBasketRest('1');
        $manager->persist($newNumberBaskRest);
        $manager->flush();

        return $this->redirectToRoute('basket_compouned',['id' => $id]);
    }

    /**
     *@Route("/deleteProdBaskComp/{id}/{name}", name="delete_prod_bask_comp") 
     */
    public function deleteProdBaskComp(ProdBaskCompRepository $repoC, ProdOfWeekRepository $repo, ObjectManager $manager, $id, $name)
    {
        $prodNameUnity = $repo->findBy(array('prodByUnity' => $name));
        foreach($prodNameUnity as $quantity){
            $newQuantityProdUnity = $quantity->getQuantityProdUnity();//récupère le nombre de produit
        }
        $prodNameKg = $repo->findBy(array('prodByKg' => $name));
        foreach($prodNameKg as $quantity){
            $newQuantityProdKg = $quantity->getQuantityProdKg();//récupère le nombre de produit
        }

        if($prodNameUnity == null){
            $newQuantity = $quantity->setQuantityProdKg($newQuantityProdKg + '+1');
        }else{
            $newQuantity = $quantity->setQuantityProdUnity($newQuantityProdUnity + '+1');
        }
        $manager->persist($newQuantity);
        $manager->flush();

        $prodToDelete = $repoC->find($id);
        $memberId = $prodToDelete->getMemberId();
        $manager->remove($prodToDelete);
        $manager->flush();

        return $this->redirectToRoute('basket_compouned',['id' => $memberId]);
    }

    /**
     * @Route("/basket_compouned/{id}", name="basket_compouned")//appel via le lien du menu
     * @Route("/basket_compouned/{id}/{name}", name="basket_comp")//appel lors de la compositon du panier
     */
    public function basket_compouned(ProdBaskComp $ProdBaskComp = null, ProdOfWeekRepository $repo, ProdBaskCompRepository $repoC, ObjectManager $manager, $id = null, $name = null)
    {
        $memberId = $id;
        $nameProd = $name;

        /*récupère l'entrée corresp au nom du produit $nameProd*/
        $prodUnityExist = $repo->findBy(//récupère ds le champ prodByUnity de la table des produits celui
            // dont le nom correspond au nom du produit $nameProd
            array('prodByUnity' => $nameProd)//afin de vérifier si c'est un produit au kilo ou à l'unité
        );
        foreach($prodUnityExist as $quantity){
            $newQuantityProdUnity = $quantity->getQuantityProdUnity();//récupère le nombre de produit
            $idProdUnity = $quantity->getId();//récupère l'identifiant
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
            if($newQuantityProdKg == '1'){//si la quantité du produit est de 1
                $prodToDelete = $repo->find($idProdKg);//récupère l'entrée corresp à l'id du produit
                $manager->remove($prodToDelete);//efface ce produit des produits de la semaine
            }else{
                $newQuantity = $quantity->setQuantityProdKg($newQuantityProdKg + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
                $manager->persist($newQuantity);
                $manager->flush();
            }
        }else{//donc produit à l'unité !!
            $unityOrKg = 'unité';//définit le mode de vente
            if($newQuantityProdUnity == '1'){
                $prodToDelete = $repo->find($idProdUnity);
                $manager->remove($prodToDelete);
            }else{
                $newQuantity = $quantity->setQuantityProdUnity($newQuantityProdUnity + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
                $manager->persist($newQuantity);
                $manager->flush();
            }
        }
        /***/

        /*insère un produit ds la table des paniers composés en relation avec l'id du membre*/
        if($nameProd){
            $ProdBaskComp = new ProdBaskComp();
            $ProdBaskComp->setMemberId($id);
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
            'id' => $id,
            'basketMember' => $basketMember,//en lien avec la ligne 49
            'prodByUnity' => $prodByUnity,//en lien à la ligne 38
            'prodByKg' => $prodByKg//en lien à la ligne 39
        ]);
    }
}