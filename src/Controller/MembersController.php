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
// use Symfony\Component\HttpFoundation\Session\Session;
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
    public function showListProdOfWeek()
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
            $quantityOfThisProduct = $quantity->getQuantity();//récupère la quantité disponible du produit dans les produits de la semaine
        }

        $prodsOfMember = $repoC->find($id);//récupère l'entrée de la table correspondant à l'id du produit
        $quantityProdOfThisMember = $prodsOfMember->getQuantityProd();//récupère la quantité de produit du produit mis dans le panier
        dump($prodsOfMember);

        $newQuantity = $quantity->setQuantity($quantityOfThisProduct + $quantityProdOfThisMember);//rajoute les produits du panier composé pour les remettre dans les produits de la semaine
        $manager->persist($newQuantity);
        $manager->flush();

        $manager->remove($prodsOfMember);//efface le produit du panier
        $manager->flush();

        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * fonction permettant de composer son panier(partie 1) et renvoyer à la vue la liste des produits de la semaine et ceux du panier du membre(partie 2)
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
        /**********(partie 1) : traitement pour composer un panier***********/
        $member = $this->getUser();
        $nameProd = $name;

        /*récupère le produit correspondant au nom du produit $nameProd dans les produits de la semaine*/
        $prodExist = $repo->findBy(array('nameProd' => $nameProd));

        foreach($prodExist as $quantity){
            $newQuantityProd = $quantity->getQuantity();//récupère le nombre de produit
            $saleType = $quantity->getSaleType();//récupère le type de vente
            // $idProdUnity = $quantity->getId();//récupère l'identifiant
        }
        
        if(!empty($prodExist)){//vérifie si le tableau est vide donc si un produit existe ds la table prodBaskComp
            if($newQuantityProd == '0'){
                $nameProd = null;
            }else{
                $newQuantity = $quantity->setQuantity($newQuantityProd + '-1');//permet de déduire la quantité de produits ds la table des produits de la semaine
                $manager->persist($newQuantity);
                $manager->flush();
            }
        }

        /*insère un produit ds le panier composé du membre en relation avec l'id du membre*/
        if($nameProd){//si un nom de produit est présent ds l'url
            $prodsOfMember = $repoC->findBy(//récupère tous les prod correspondant à l'id du membre
                array('members' => $member)
            );
            foreach($prodsOfMember as $memberIdProd){
                $prodExist = $memberIdProd->getNameProd();//récupère les noms des produits ds le tableau
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
            $ProdBaskComp->setMembers($member);
            $ProdBaskComp->setNameProd($name);
            $ProdBaskComp->setKgOrUnity($saleType);
            $ProdBaskComp->setQuantityProd('1');

            $manager->persist($ProdBaskComp);
            $manager->flush();
        }
        /**************/
        
        /******(partie 2) : traitement pour renvoyer à l'affichage*********/
        //va chercher ds le champ members de la table des paniers composés tous le produits correspondant au numéro du membre en vue d'afficher la liste du panier du membre
        $basketMember = $repoC->findBy(array('members' => $member));

        //permet de récupérer tous les produits du champ nameprod pour afficher la liste des produits de la semaine
        $prod = $repo->findAll('nameProd');
        /**************/

        return $this->render('members/basketCompounedMembers.html.twig', [
            'basketMember' => $basketMember,//en lien avec la ligne 216
            'prod' => $prod//en lien à la ligne 219
        ]);
    }
}