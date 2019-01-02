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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**  @Route("/members") */
class MembersController extends AbstractController
{
    /**
     * fonction qui définit le type de panier choisit par le membre
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * 
     * @Route("/chooseBasketType", name="choose_basket_type")
     */
    public function chooseBasketType(MembersRepository $repoM, ObjectManager $manager){
        if((isset($_POST['check1'])) && (isset($_POST['check2'])) && (isset($_POST['dayOfWeek']))){
            $member = $repoM->find($this->getUser()->getId());
            dump($member);
            $definedBasketType = $member->setBasketType('composés');
            $definedBasketTypeBis = $member->setBasketTypeBis('collectés');
            $definedNumberBasketCompouned = $member->setNumberBasketCompouned('0');
            $definedNumberBasketCollected = $member->setNumberBasketCollected('0');
            $definedDayOfWeek = $member->setDayOfWeek($_POST['dayOfWeek']);
            $manager->persist($definedBasketType);
            $manager->persist($definedBasketTypeBis);
            $manager->persist($definedNumberBasketCompouned);
            $manager->persist($definedNumberBasketCollected);
            $manager->persist($definedDayOfWeek);
            $manager->flush();
        }else if(isset($_POST['check1'])){
            $member = $repoM->find($this->getUser()->getId());
            $definedBasketType = $member->setBasketType('composés');
            $definedNumberBasketCompouned = $member->setNumberBasketCompouned('0');
            $manager->persist($definedBasketType);
            $manager->persist($definedNumberBasketCompouned);
            $manager->flush();
        }else if((isset($_POST['check2'])) && (isset($_POST['dayOfWeek']))){
            $member = $repoM->find($this->getUser()->getId());
                $definedBasketType = $member->setBasketTypeBis('collectés');
            $definedNumberBasketCollected = $member->setNumberBasketCollected('0');
            $definedDayOfWeek = $member->setDayOfWeek($_POST['dayOfWeek']);
            $manager->persist($definedBasketType);
            $manager->persist($definedNumberBasketCollected);
            $manager->persist($definedDayOfWeek);
            $manager->flush();
        }
        
        return $this->redirectToRoute('my_compte');
    }
    /**
     * fonction pour accéder à la page du compte du membre
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * 
     * @Route("/", name="my_compte")
     */
    public function showInfoMember(ProdBaskCompRepository $repoC)
    {
        $memberId = $this->getUser()->getId();
        // dump($memberId);
        $prodbaskOfMember = $repoC->findBy(
            array('members' => $memberId));
            if($prodbaskOfMember != null){
                foreach($prodbaskOfMember as $date){
                    $date = $date->getCreatedAt();
                }
                // dump($date);exit;
                return $this->render('members/accountMembers.html.twig', [
                    'date' => $date
                ]);
            }
        return $this->render('members/accountMembers.html.twig');
    }

    /**
     * fonction permettant de valider le panier en initialisant le nombre de panier restant à 1(partie 1) et d'actualiser les quantités dans les produits de la semaine(partie 2)
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param repository $repo
     * parameter converter pour parler avec la table prodOfWeek
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * 
     * @Route ("/validateBask", name="validate_bask")
     */
    public function validateBask(ProdBaskCompRepository $repoC, ProdOfWeekRepository $repo, MembersRepository $repoM, ObjectManager $manager)
    {
        /*partie 1*/
        $member = $repoM->find($this->getUser()->getId());
        $memberId = $member->getId();
        $prodOfMember = $repoC->findBy(array('members' => $memberId));
        if($prodOfMember){
            $newNumberBaskRest = $member->setNumberBasketCompouned('1');
            $manager->persist($newNumberBaskRest);
            $manager->flush();
        }
        /****/

        /*partie 2*/
        $prodsOfMember = $repoC->findBy(array('members' => $this->getUser()->getId()));//récupère les produits correspondant à l'id du membre
        foreach($prodsOfMember as $prodOfMember){
            $nameProd = $prodOfMember->getNameProd();
            $idProdBaskComp = $prodOfMember->getId();
            $quantityProdOfThisMember = $prodOfMember->getQuantityProd();
            $prodName = $repo->findBy(array('nameProd' => $nameProd));
            foreach($prodName as $quantity){
                $quantityOfThisProduct = $quantity->getQuantity();//récupère la quantité du produit dans les produits de la semaine
                if($quantityOfThisProduct == '0'){//si il n'y a plus de produit dispo
                    $deleteProd = $repoC->find($idProdBaskComp);//va chercher ce produit dans le panier
                    $manager->remove($deleteProd);//supprime le du panier
                    $manager->flush();
                }else if($quantityOfThisProduct - $quantityProdOfThisMember <= -1 ){//si il y plus de produit dans le panier que de produit dispo
                    $noMoreQuantityProduct = 'yes';
                    $newQuantityProdOfMember = $prodOfMember->setQuantityProd($quantityOfThisProduct);//met le nombre de produit dispo dans le panier
                    $manager->persist($newQuantityProdOfMember);
                    $newQuantityProdOfWeek = $quantity->setQuantity('0');//définit le nombre de produit de la semaine à 0
                    $manager->persist($newQuantityProdOfWeek);
                    $manager->flush();

                    return $this->redirectToRoute('basket_validate', ['noMoreQuantityProduct' => $noMoreQuantityProduct] );
                }else{
                    $newQuantity = $quantity->setQuantity($quantityOfThisProduct - $quantityProdOfThisMember);//enlève les produits du panier composé dans les produits de la semaine
                    $manager->persist($newQuantity);
                    $manager->flush();
                }
            }
        }
        /****/

        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * fonction permettant de remettre les produits dans les produits de la semaine(partie 1), d'enlever la validation du panier(partie 2)puis de vider le panier(partie 3)
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param repository $repo
     * parameter converter pour parler avec la table prodOfWeek
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * 
     * @Route("/deleteAllBaskOfMember", name="delete_all_bask_of_member")
     */
    public function deleteAllBaskOfMember(ProdBaskCompRepository $repoC, ProdOfWeekRepository $repo, MembersRepository $repoM, ObjectManager $manager)
    {
        $noMoreQuantityProduct = false;
        $member = $repoM->find($this->getUser());
        $validationBaskOrNot = $member->getNumberBasketCompouned();
        if($validationBaskOrNot == '1'){
            /*partie 1*/
            $prodsOfMember = $repoC->findBy(array('members' => $this->getUser()->getId()));//récupère les produits correspondant à l'id du membre
            foreach($prodsOfMember as $prodOfMember){
                $nameProd = $prodOfMember->getNameProd();
                $quantityProdOfThisMember = $prodOfMember->getQuantityProd();
                $prodName = $repo->findBy(array('nameProd' => $nameProd));
                foreach($prodName as $quantity){
                    $quantityOfThisProduct = $quantity->getQuantity();//récupère la quantité du produit dans les produits de la semaine
                        $newQuantity = $quantity->setQuantity($quantityOfThisProduct + $quantityProdOfThisMember);//enlève les produits du panier composé dans les produits de la semaine
                        $manager->persist($newQuantity);
                        $manager->flush();
                }
                /*partie 3*/
                $manager->remove($prodOfMember);//efface les produits du panier
                $manager->flush();
                /*****/
            }
            /*partie 2*/
            $member = $repoM->find($this->getUser());
            $switchOfValidationBask = $member->setNumberBasketCompouned('0');
            $manager->persist($switchOfValidationBask);
            $manager->flush();
            /*****/
        }
        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * fonction servant à enlever un produit du panier composé
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param string $name
     * nom du produit à enlever du panier
     * @param int $id
     * identifiant du produit à enlever du panier
     * 
     *@Route("/deleteProdBaskComp/{id}", name="delete_prod_bask_comp") 
     */
    public function deleteProdBaskComp(ProdBaskCompRepository $repoC, MembersRepository $repoM, ObjectManager $manager, $id)
    {
        $member = $repoM->find($this->getUser());
        $baskValidateOrNot = $member->getNumberBasketCompouned();
        if($baskValidateOrNot == '0'){
            $prodsOfMember = $repoC->find($id);//récupère l'entrée de la table correspondant à l'id du produit
            $manager->remove($prodsOfMember);//efface le produit du panier
            $manager->flush();
        }
        return $this->redirectToRoute('basket_compouned');
    }

        /**
     * fonction servant à enlever un kilo ou une unité du produit du panier composé
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param string $name
     * nom du produit à enlever du panier
     * @param int $id
     * identifiant du produit à enlever du panier
     * 
     *@Route("/deleteOneProdBaskComp/{id}", name="delete_one_prod_bask_comp") 
     */
    public function deleteOneProdBaskComp(ProdBaskCompRepository $repoC, MembersRepository $repoM, ObjectManager $manager, $id)
    {
        $member = $repoM->find($this->getUser());
        $baskValidateOrNot = $member->getNumberBasketCompouned();
        if($baskValidateOrNot == '0'){//si le panier n'est pas validé
            $prodsOfMember = $repoC->find($id);//récupère l'entrée de la table correspondant à l'id du produit
            $quantity = $prodsOfMember->getQuantityProd();
            if($quantity > 1){
                $newQuantity = $prodsOfMember->setQuantityProd($quantity - 1);
                $manager->persist($newQuantity);
                $manager->flush();
            }else{
                $manager->remove($prodsOfMember);//efface le produit du panier
                $manager->flush();
            }
        }
        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * fonction permettant de composer son panier(partie 1) et renvoyer à la vue la liste des produits de la semaine et ceux du panier du membre(partie 2)
     * 
     * @param object $ProdBaskComp
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
     * @Route("/basket_compouned", name="basket_compouned")
     * @Route("/{name}", name="basket_comp")//appel lors de la compositon du panier
     * @Route("/{noMoreQuantityProduct}", name="basket_validate")
     */
    public function basketCompouned(ProdBaskComp $ProdBaskComp = null, ProdOfWeekRepository $repo, MembersRepository $repoM, ProdBaskCompRepository $repoC, ObjectManager $manager, $name = null, $noMoreQuantityProduct = null)
    {
        $noMoreQuantityProduct = $name;
        /**********(partie 1) : traitement pour composer un panier***********/
        $member = $this->getUser();
        $nameProd = $name;

        /*récupère le produit correspondant au nom du produit $nameProd dans les produits de la semaine*/
        $prodExist = $repo->findBy(array('nameProd' => $nameProd));

        /*vérifie si le panier est validé*/
        $member = $repoM->find($this->getUser());
        $baskValidateOrNot = $member->getNumberBasketCompouned();

        foreach($prodExist as $quantity){
            $newQuantityProd = $quantity->getQuantity();//récupère le nombre de produit
            $saleType = $quantity->getSaleType();//récupère le type de vente
            if($newQuantityProd != '0' && $baskValidateOrNot == '0'){//si il reste des produits dispo et si le panier n'est pas validé
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
                    $ProdBaskComp->setCreatedAt(new \DateTime());
        
                    $manager->persist($ProdBaskComp);
                    $manager->flush();
                }
            }
        }
        /**************/
        
        /******(partie 2) : requète pour renvoyer à l'affichage*********/
        //va chercher ds le champ members de la table des paniers composés tous le produits correspondant au numéro du membre en vue d'afficher la liste du panier du membre
        $basketMember = $repoC->findBy(array('members' => $member));

        //permet de récupérer tous les produits du champ nameprod pour afficher la liste des produits de la semaine
        $prod = $repo->findAll('nameProd');
        /**************/

        return $this->render('members/basketCompounedMembers.html.twig', [
            'noMoreQuantityProduct' => $noMoreQuantityProduct,
            'basketMember' => $basketMember,
            'prod' => $prod
        ]);
    }
}