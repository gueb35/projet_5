<?php

namespace App\Controller;

use App\Entity\Members;
use App\Entity\ProdOfWeek;
use App\Entity\ProdBaskComp;
use App\Repository\MembersRepository;
use App\Repository\ProdOfWeekRepository;
use App\Repository\ProdBaskCompRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * fonction permettant d'enlever un produits dans les produits de la semaine
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
     * fonction pour définir les produits de la semaine(partie 1)
     * et afficher la liste des produits de la semaine(partie 2)
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
        /**partie 1**/
        if(!$ProdOfWeek){
            $ProdOfWeek = new ProdOfWeek();
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

        /**partie 2**/
        $prodsOfWeek = $repo->findAll('nameProd');
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
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du membre
     * @param int $groupOfMember1
     * groupe de membre du panier composé
     * @param int $groupOfMember2
     * groupe de membre du panier collecté
     * 
     * @Route("/initialize/{id}/{groupOfMember1}/{groupOfMember2}", name="initialize_number_basket_rest")
     */
    public function initializeNumberBasketRest(MembersRepository $repoM, ObjectManager $manager, $id, $groupOfMember1, $groupOfMember2)
    {
        $memberId = $repoM->find($id);
        $newQuantity = $memberId->setNumberBasketCollected('44');
        $manager->persist($newQuantity);
        $manager->flush();

        return $this->redirectToRoute('paginationMemberGroup', ['groupOfMember1' => $groupOfMember1, 'groupOfMember2' => $groupOfMember2]);
    }


    /**
     * fonction qui affiche une liste de 5 membres
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param int $groupOfMember1
     * groupe de membre du panier composé
     * @param int $groupOfMember2
     * groupe de membre du panier collecté
     * 
     * @Route("/pagination_member_group/{groupOfMember1}/{groupOfMember2}", name="paginationMemberGroup")
     */
    public function paginationMemberGroup(MembersRepository $repoM, $groupOfMember1 = null, $groupOfMember2 = null)
    {
        $groupOfMember1Max = count($repoM->findBy(
            array('basketType' => 'composés')
        ));
        $groupOfMember2Max = count($repoM->findBy(
            array('basketTypeBis' => 'collectés')
        ));

        $membersComp = $repoM->findBy(
                array('basketType' => 'composés'),
                array('createdAt' => 'desc'),
                5,
                $groupOfMember1);
    
            $membersColl = $repoM->findBy(
                array('basketTypeBis' => 'collectés'),
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
     * parameter converter pour parler avec la table members
     * 
     * @Route("/membersList", name="members_list")
     */
    public function membersList(MembersRepository $repoM)
    {
        $groupOfMember1Max = count($repoM->findBy(
            array('basketType' => 'composés')
        ));
        $groupOfMember2Max = count($repoM->findBy(
            array('basketTypeBis' => 'collectés')
        ));

        $groupOfMember1 = 0;
        $groupOfMember2 = 0;

        $membersComp = $repoM->findBy(
            array('basketType' => 'composés'),
            array('createdAt' => 'desc'),
            5,
            $groupOfMember1);
        $membersColl = $repoM->findBy(
            array('basketTypeBis' => 'collectés'),
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
     * fonction permettant de vider la table des paniers composés
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

        $definedAllBaskRest = $repoM->findBy(
            array('basketType' => 'composés'));

        foreach($definedAllBaskRest as $newQuantityBAskRest){
            $newQuantity = $newQuantityBAskRest->setNumberBasketCompouned('0');
        }

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
     * @param int $memberId
     * identifiant du membre
     * 
     * @Route("/deleteBaskMember/{memberId}", name="delete_bask_member")
     */
    public function deleteBaskMember(MembersRepository $repoM, ObjectManager $manager, ProdBaskCompRepository $repoC, $memberId)
    {
        $member = $repoM->find($memberId);
        $newQuantity = $member->setNumberBasketCompouned('0');
        $manager->persist($newQuantity);
        $manager->flush();

        $prodsBaskCompOfMember = $repoC->findBy(
            array('members' => $memberId)
        );

        foreach($prodsBaskCompOfMember as $delete){
            $manager->remove($delete);
            $manager->flush();
        }
        return $this->redirectToRoute('basket_compouned_list');
    }

    /**
     * fonction qui affiche la liste des membres du panier composés
     * 
     * @param object $paginator
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param repository $repoC
     * parameter converter pour parler avec la table prodBaskComp
     * @param object $request
     * 
     * @Route("/basketComp", name="basket_compouned_list")
     */
    public function showBaskCompListMembers(PaginatorInterface $paginator, MembersRepository $repoM, ProdBaskCompRepository $repoC, Request $request)
    {
        $allMembers = $repoM->findBy(
            array('basketType' => 'composés',
            'numberBasketCompouned' => '1'
        ),
            array('createdAt' => 'desc')
        );

        $members = $paginator->paginate(
            $allMembers,
            $request->query->getInt('page', 1),
            5
        );

        $prodBaskMember = $repoC->findAll();
        
        return $this->render('admin/basketComp.html.twig', [
            'members' => $members,
            'prodBaskMember' => $prodBaskMember

        ]);
    }

    /**
     * fonction qui définit le nombre de paniers collectés restant dû au membre
     * 
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param int $id
     * identifiant du membre
     * 
     *@Route("/definedNumberBasketRest/{id}", name="defined_number_basket_rest") 
     */
    public function definedNumberBasketRest(MembersRepository $repoM, ObjectManager $manager, $id)
    {
        $memberId = $repoM->find($id);
        $newQuantityBasketRest = $memberId->getNumberBasketCollected();
        $newQuantity = $memberId->setNumberBasketCollected($newQuantityBasketRest + '-1');
        $manager->flush();

        return $this->redirectToRoute('basket_collected');
    }
    
    /**
     * fonction qui affiche la liste des membres du panier collectés
     * 
     * @param object $paginator
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * @param object $request
     * 
     * @Route("/basketColl", name="basket_collected")
     */
    public function showBaskCollList(PaginatorInterface $paginator, MembersRepository $repoM, Request $request)
    {
        $day = $request->query->get('chooseDayOfWeek', 'lundi');
        $allMembers = $repoM->listOfMembersByDay($day);

        $members = $paginator->paginate(
            $allMembers,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/basketColl.html.twig',[
            'members' => $members
        ]);
    }
}