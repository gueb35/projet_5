<?php

namespace App\Controller;

use App\Entity\Members;
use App\Entity\ProdOfWeek;
use App\Entity\ProdBaskComp;
use App\Repository\MembersRepository;
use App\Repository\ProdOfWeekRepository;
use App\Repository\ProdBaskCompRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**  @Route("/members") */
class MembersController extends AbstractController
{
    /**
     * fonction pour accéder à la page du compte du membre
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * 
     * @Route("/", name="my_compte")
     */
    public function showListProdOfWeek(MembersRepository $repoM)
    {
        return $this->render('members/accountMembers.html.twig', [
        ]);
    }

    /**
     * fonction permettant de valider le panier en initialisant le nombre de panier restant à 1
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

        $newNumberBaskRest = $repoM->find($this->getUser()->getId());
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
     * @Route("/{name}", name="basket_comp")//appel lors de la compositon du panier
     */
    public function basket_compouned(MembersRepository $repoM, ProdBaskComp $ProdBaskComp = null, ProdOfWeekRepository $repo, ProdBaskCompRepository $repoC, ObjectManager $manager, $name = null)
    {
        $member = $this->getUser();
        $nameProd = $name;

        /*récupère l'entrée corresp au nom du produit $nameProd*/
        $prodExist = $repo->findBy(array('nameProd' => $nameProd));//($nameProd) afin de vérifier si c'est un produit au kilo ou à l'unité

        foreach($prodExist as $quantity){
            $newQuantityProd = $quantity->getQuantity();//récupère le nombre de produit
            $saleType = $quantity->getSaleType();
            $idProdUnity = $quantity->getId();//récupère l'identifiant
        }
        
        if(!empty($prodExist)){//vérifie si le tableau est vide
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
                array('member_id' => $member)
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
            $ProdBaskComp->setMemberId($member->getId());
            $ProdBaskComp->setNameProd($name);
            $ProdBaskComp->setKgOrUntity($saleType);
            $ProdBaskComp->setQuantityProd('1');

            $manager->persist($ProdBaskComp);
            $manager->flush();
        }
        
        //va chercher ds le champ member_id de la table des paniers composés
        //toutes les entrées correspondant au numéro du membre
        $basketMember = $repoC->findBy(array('member_id' => $member));

        //permet de récupérer tous les produits du champ nameprod pour afficher la liste
        $prod = $repo->findAll('nameProd');

        return $this->render('members/basketCompounedMembers.html.twig', [
            'basketMember' => $basketMember,//en lien avec la ligne 216
            'prod' => $prod//en lien à la ligne 219
        ]);
    }
}