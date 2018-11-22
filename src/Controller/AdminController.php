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
use App\Entity\ProdOfWeek;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="home_admin")
     */
    public function homeAdmin($id = null)
    {
        return $this->render('admin/homeAdmin.html.twig',[
            'id' => $id
        ]);
    }

    /**
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
     * @Route("/prodOfWeek/{id}", name="product_of_the_week")
     * @Route("/prodOfWeek/new", name="product_of_the_weeks")
     */
    public function formProdOfWeek(ProdOfWeek $ProdOfWeek =null, ProdOfWeek $ProdOfWeek2 =null, ProdOfWeekRepository $repo, Request $request, ObjectManager $manager, $id)
    {
        $prodOfWeekComp = $repo->prodByUnity();//permet de récupérer tous les produits du champ proByUnity
        $prodOfWeekColl = $repo->prodByKg();//permet de récupérer tous les produits du champ proByKg
/********************************** formbyunity**************************************/
        if(!$ProdOfWeek){//si le produit n'existe pas
            $ProdOfWeek = new ProdOfWeek();//crée un nouvel objet
        }
        
        $formProdOfWeekUnity1 = $this->createFormBuilder($ProdOfWeek)
            ->add('prodByUnity',TextType::class)
            ->add('quantityProdUnity',IntegerType::class)
            ->getForm();
        $formProdOfWeekUnity1->handleRequest($request);
        if($formProdOfWeekUnity1->isSubmitted() && $formProdOfWeekUnity1->isValid()) {
            $manager->persist($ProdOfWeek);
            $manager->flush();

            return $this->redirectToRoute('product_of_the_week', ['id' => $ProdOfWeek->getId()]);
        }
/********************************** formbykg**************************************/
        if(!$ProdOfWeek2){//si le produit n'existe pas
            $ProdOfWeek2 = new ProdOfWeek();//crée un nouvel objet
        }
        
        $formProdOfWeekByKg1 = $this->createFormBuilder($ProdOfWeek2)
            ->add('prodByKg',TextType::class)
            ->add('quantityProdKg',IntegerType::class)
            ->getForm();
        $formProdOfWeekByKg1->handleRequest($request);
        if($formProdOfWeekByKg1->isSubmitted() && $formProdOfWeekByKg1->isValid()) {
            $manager->persist($ProdOfWeek2);
            $manager->flush();

            return $this->redirectToRoute('product_of_the_week', ['id' => $ProdOfWeek2->getId()]);
        }
        return $this->render('admin/prodWeek.html.twig', [

            'id' => $id,
            'formProdOfWeekUnity1' => $formProdOfWeekUnity1->createView(),
            'formProdOfWeekByKg1' => $formProdOfWeekByKg1->createView(),
            'prodOfWeekComp' => $prodOfWeekComp,
            'prodOfWeekColl' => $prodOfWeekColl
        ]);
    }

    /**
     * @Route("/membersList", name="members_list")
     */
    public function membersList($id = null)
    {
        $repo = $this->getDoctrine()->getRepository(Members::class);
        $membersComp = $repo->findBy(
            array('basketType' => 'composés')
        );
        $membersColl = $repo->findBy(
            array('basketType' => 'collectés')
        );
        return $this->render('admin/membersList.html.twig', [
            'members1' => $membersComp,
            'members2' => $membersColl
        ]);
    }

    /**
     * @Route("/basketComp/{id}", name="basket_compouned_list")
     */
    public function showBaskCompList(MembersRepository $repo, ProdBaskCompRepository $repoC, $id = null)
    {
        $baskComps = $repo->findBy(//récupère toutes les entrées
            array('basketType' => 'composés')//correspondant à "composés" ds le champ "basketType"
        );
        dump($baskComps);
        foreach($baskComps as $memberId){
            $membersId = $memberId->getId();//récupère l'identifiant en référence avec l'id du membre
            dump($membersId);//récupère tous les id des membre du panier composé
            $member = $repo->find($memberId);
            dump($member);
            // $prodBaskMember = $repoC->findBy(
            //     array('member_id' => $membersId)
            // );
            // dump($prodBaskMember);
            // dump($prodBaskMember);exit;
        }
        // dump($membersId);exit;//sert seulement a stoppé le traitement
        $prodBaskMember = $repoC->findBy(
            array('member_id' => $membersId)
        );
        dump($prodBaskMember);exit;
        
        // return $this->render('admin/basketComp.html.twig', [
        //     'id' => $id,
        //     'baskComps' => $baskComps,
        //     'prodBaskMember' => $prodBaskMember
        // ]);
    }
    
    /**
     * @Route("/basketColl/{id}", name="basket_collected")
     */
    public function showBaskCollList($id = null)
    {
        $repo = $this->getDoctrine()->getRepository(Members::class);
        $dayBaskColl1 = $repo->findBy(
            array('dayOfWeek' => 'lundi')
        );
        $dayBaskColl2 = $repo->findBy(
            array('dayOfWeek' => 'mardi')
        );
        $dayBaskColl3 = $repo->findBy(
            array('dayOfWeek' => 'mercredi')
        );
        $dayBaskColl4 = $repo->findBy(
            array('dayOfWeek' => 'jeudi')
        );
        $dayBaskColl5 = $repo->findBy(
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
