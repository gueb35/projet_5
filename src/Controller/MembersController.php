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
     * @Route("/basket_compouned/{id}", name="basket_compouned")//appel via le lien du menu
     * @Route("/basket_compouned/{id}/{name}", name="basket_comp")//appel lors de la compositon du panier
     */
    public function basket_compouned(ProdBaskComp $ProdBaskComp = null, ProdOfWeekRepository $repo, ProdBaskCompRepository $repoC, ObjectManager $manager, $id = null, $name = null)
    {
        $memberId = $id;
        $nameProd = $name;

        /*permet de savoir si le produit correspondant au nom du produit($nameProd) ds la colonne des prod à l'unité*/
        $prodUnityExist = $repo->findBy(//va chercher ds le champ prodByUnity de la table des produits celui
            // dont le nom correspond au nom du produit $nameProd
            array('prodByUnity' => $nameProd)//afin de vérifier si c'est un produit au kilo ou à l'unité
        );dump($prodUnityExist);

        foreach($prodUnityExist as $quantity){
            $newQuantityProdUnity = $quantity->getQuantityProdUnity();//récupère le nombre de produit
            $idProdUnity = $quantity->getId();
        }

        /*permet de savoir si le produit correspondant au nom du produit($nameProd) ds la colonne des prod au kg*/
        $prodKgExist = $repo->findBy(
            array('prodByKg' => $nameProd)
        );
        foreach($prodKgExist as $quantity){
            $newQuantityProdKg = $quantity->getQuantityProdKg();//récupère le nombre de produit
            $idProdKg = $quantity->getId();
        }

        if($prodUnityExist == null){//vérifie si ce nom existe(est null)
            $unityOrKg = 'kg';
            if($newQuantityProdKg == '1'){
                $prodToDelete = $repo->find($idProdKg);
                $manager->remove($prodToDelete);
            }else{
                $newQuantity = $quantity->setQuantityProdKg($newQuantityProdKg + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
                $manager->persist($newQuantity);
                $manager->flush();
            }
        }else{
            $unityOrKg = 'unité';
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
            $ProdBaskComp = new ProdBaskComp();
            $ProdBaskComp->setMemberId($id);
            $ProdBaskComp->setNameProd($name);
            $ProdBaskComp->setKgOrUntity($unityOrKg);
            $ProdBaskComp->setQuantityProd('1');

            $manager->persist($ProdBaskComp);
            $manager->flush();
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