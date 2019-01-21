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
    public function chooseBasketType(MembersRepository $repoM, ObjectManager $manager)
    {
        $member = $repoM->find($this->getUser()->getId());

        if((isset($_POST['check1'])) && (isset($_POST['check2'])) && (isset($_POST['dayOfWeek']))){

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

            $definedBasketType = $member->setBasketType('composés');
            $definedNumberBasketCompouned = $member->setNumberBasketCompouned('0');

            $manager->persist($definedBasketType);
            $manager->persist($definedNumberBasketCompouned);
            $manager->flush();

        }else if((isset($_POST['check2'])) && (isset($_POST['dayOfWeek']))){

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
        $prodbaskOfMember = $repoC->findBy(
            array('members' => $this->getUser()->getId()));
            if($prodbaskOfMember != null){
                foreach($prodbaskOfMember as $date){
                    $date = $date->getCreatedAt();
                }

                return $this->render('members/accountMembers.html.twig', [
                    'date' => $date
                ]);
            }
        return $this->render('members/accountMembers.html.twig');
    }

    /**
     * fonction permettant de valider le panier en initialisant le nombre de panier restant à 1(partie 1)
     * et d'actualiser les quantités dans les produits de la semaine(partie 2)
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param repository $repo
     * parameter converter pour parler avec la table prodOfWeek
     * @param object $manager
     * parameter converter pour manipuler des données
     * 
     * @Route ("/validateBask", name="validate_bask")
     */
    public function validationBasketCompouned(ProdBaskCompRepository $repoC, ProdOfWeekRepository $repo, ObjectManager $manager)
    {
        /*partie 1*/
        $prodOfMember = $repoC->findBy(array('members' => $this->getUser()->getId()));
        if($prodOfMember){
            $newNumberBaskRest = $this->getUser()->setNumberBasketCompouned('1');
            $manager->persist($newNumberBaskRest);
            $manager->flush();
        }
        /****/

        /*partie 2*/
        $prodsOfMember = $repoC->findBy(array('members' => $this->getUser()->getId()));
        foreach($prodsOfMember as $prodOfMember){
            $nameProd = $prodOfMember->getNameProd();
            $idProdBaskComp = $prodOfMember->getId();
            $quantityProdOfThisMember = $prodOfMember->getQuantityProd();
            $prodName = $repo->findBy(array('nameProd' => $nameProd));
            foreach($prodName as $quantity){
                $quantityOfThisProduct = $quantity->getQuantity();
                if($quantityOfThisProduct == '0'){
                    $deleteProd = $repoC->find($idProdBaskComp);
                    $manager->remove($deleteProd);
                    $manager->flush();
                }else if($quantityOfThisProduct - $quantityProdOfThisMember <= -1 ){
                    $noMoreQuantityProduct = 'yes';
                    $newQuantityProdOfMember = $prodOfMember->setQuantityProd($quantityOfThisProduct);
                    $manager->persist($newQuantityProdOfMember);
                    $newQuantityProdOfWeek = $quantity->setQuantity('0');
                    $manager->persist($newQuantityProdOfWeek);
                    $manager->flush();

                    return $this->redirectToRoute('basket_validate', ['noMoreQuantityProduct' => $noMoreQuantityProduct] );
                }else{
                    $newQuantity = $quantity->setQuantity($quantityOfThisProduct - $quantityProdOfThisMember);
                    $manager->persist($newQuantity);
                    $manager->flush();
                }
            }
        }
        /****/

        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * fonction permettant de remettre les produits dans les produits de la semaine(partie 1),
     *  d'enlever la validation du panier(partie 2)puis de vider le panier(partie 3)
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param repository $repo
     * parameter converter pour parler avec la table prodOfWeek
     * @param object $manager
     * parameter converter pour manipuler des données
     * 
     * @Route("/deleteAllBaskOfMember", name="delete_all_bask_of_member")
     */
    public function deleteAllBaskOfMember(ProdBaskCompRepository $repoC, ProdOfWeekRepository $repo, ObjectManager $manager)
    {
        $noMoreQuantityProduct = false;

        $validationBaskOrNot = $this->getUser()->getNumberBasketCompouned();
        if($validationBaskOrNot == '1'){
            /*partie 1*/
            $prodsOfMember = $repoC->findBy(array('members' => $this->getUser()->getId()));
            foreach($prodsOfMember as $prodOfMember){
                $nameProd = $prodOfMember->getNameProd();
                $quantityProdOfThisMember = $prodOfMember->getQuantityProd();
                $prodName = $repo->findBy(array('nameProd' => $nameProd));
                foreach($prodName as $quantity){
                    $quantityOfThisProduct = $quantity->getQuantity();
                        $newQuantity = $quantity->setQuantity($quantityOfThisProduct + $quantityProdOfThisMember);
                        $manager->persist($newQuantity);
                        $manager->flush();
                }
                /*partie 3*/
                $manager->remove($prodOfMember);
                $manager->flush();
                /*****/
            }
            /*partie 2*/
            $switchOfValidationBask = $this->getUser()->setNumberBasketCompouned('0');
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
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param string $name
     * nom du produit à enlever du panier
     * @param int $id
     * identifiant du produit à enlever du panier
     * 
     *@Route("/deleteProdBaskComp/{id}", name="delete_prod_bask_comp") 
     */
    public function deleteProdBaskComp(ProdBaskCompRepository $repoC, ObjectManager $manager, $id)
    {
        $baskValidateOrNot = $this->getUser()->getNumberBasketCompouned();
        if($baskValidateOrNot == '0'){
            $prodsOfMember = $repoC->find($id);
            $manager->remove($prodsOfMember);
            $manager->flush();
        }
        return $this->redirectToRoute('basket_compouned');
    }

        /**
     * fonction servant à enlever un kilo ou une unité du produit du panier composé
     * 
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param string $name
     * nom du produit à enlever du panier
     * @param int $id
     * identifiant du produit à enlever du panier
     * 
     *@Route("/deleteOneProdBaskComp/{id}", name="delete_one_prod_bask_comp") 
     */
    public function deleteOneProdBaskComp(ProdBaskCompRepository $repoC, ObjectManager $manager, $id)
    {
        $baskValidateOrNot = $this->getUser()->getNumberBasketCompouned();
        if($baskValidateOrNot == '0'){
            $prodsOfMember = $repoC->find($id);
            $quantity = $prodsOfMember->getQuantityProd();
            if($quantity > 1){
                $newQuantity = $prodsOfMember->setQuantityProd($quantity - 1);
                $manager->persist($newQuantity);
                $manager->flush();
            }else{
                $manager->remove($prodsOfMember);
                $manager->flush();
            }
        }
        return $this->redirectToRoute('basket_compouned');
    }
    /**
     * fonction permettant d'enlever la validation pour pouvoir modifier le panier
     * 
     * @param object $manager
     * parameter converter pour manipuler des données
     * 
     * @Route("/modifyBaskOfMember", name="modify_bask_of_member")
     */
    public function modifyBaskOfMember(ObjectManager $manager){
        $modifyValidation = $this->getUser()->setNumberBasketCompouned("0");
        $manager->persist($modifyValidation);
        $manager->flush();

        return $this->redirectToRoute('basket_compouned');
    }

    /**
     * fonction permettant de composer son panier(partie 1)
     * et renvoyer à la vue les produits du panier du membre(partie 2)
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
     * @Route("/{noMoreQuantityProduct}", name="basket_validate")//appel lors de la validation du panier
     */
    public function basketCompouned(ProdBaskComp $ProdBaskComp = null, ProdOfWeekRepository $repo, ProdBaskCompRepository $repoC, ObjectManager $manager, $name = null, $noMoreQuantityProduct = null)
    {
        $noMoreQuantityProduct = $name;
        /**********(partie 1)**********/
        $nameProd = $name;

        $prodExist = $repo->findBy(array('nameProd' => $nameProd));

        $baskValidateOrNot = $this->getUser()->getNumberBasketCompouned();

        foreach($prodExist as $quantity){
            $newQuantityProd = $quantity->getQuantity();
            $saleType = $quantity->getSaleType();
            if($newQuantityProd != '0' && $baskValidateOrNot == '0'){

                if($nameProd){
                    $prodsOfMember = $repoC->findBy(
                        array('members' => $this->getUser())
                    );
                    foreach($prodsOfMember as $memberIdProd){
                        $prodExist = $memberIdProd->getNameProd();
                        if($prodExist == $nameProd){
                            $idProd = $memberIdProd->getId();
                            $updateProd = $repoC->find($idProd);
                            $moreQuantity = $updateProd->getQuantityProd();
                            $updateProd->setQuantityProd($moreQuantity + '1');
                
                            $manager->persist($updateProd);
                            $manager->flush();
                            return $this->redirectToRoute('basket_compouned');
                        }
                    }
        
                    $ProdBaskComp = new ProdBaskComp();
                    $ProdBaskComp->setMembers($this->getUser());
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
        
        /******(partie 2)******/

        $basketMember = $repoC->findBy(array('members' => $this->getUser()));

        $prod = $repo->findAll('nameProd');
        /**************/

        return $this->render('members/basketCompounedMembers.html.twig', [
            'noMoreQuantityProduct' => $noMoreQuantityProduct,
            'basketMember' => $basketMember,
            'prod' => $prod
        ]);
    }
}