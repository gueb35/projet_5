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
        dump($memberId);
        $nameProd = $name;
        $prodByUnity = $repo->prodByUnity();//permet de récupérer tous les produits du champ proByUnity
        $prodByKg = $repo->prodByKg();//permet de récupérer tous les produits du champ proByKg

        
        $prodcomp = $repo->findBy(//va chercher ds le champ prodByUnity de la table des produits celui dont le nom correspond au nom du produit $nameProd
            array('prodByUnity' => $nameProd)//afin de vérifier si c'est un produit au kilo ou à l'unité
        );
        dump($prodcomp);
        if($prodcomp == null){//vérifie si ce nom existe(est null)
            $unityOrKg = 'kg';
        }else{
            $unityOrKg = 'unité';
        }


        $basketMember = $repoC->findBy(//va chercher ds le champ member_id de la table des paniers composés toutes les entrées correspondant au numéro du membre
            array('member_id' => $memberId)
        );
        dump($basketMember);
        dump($name);
        dump($nameProd);
            $ProdBaskComp = new ProdBaskComp();
            $ProdBaskComp->setMemberId($id);
            $ProdBaskComp->setNameProd($name);
            $ProdBaskComp->setKgOrUntity($unityOrKg);
            $ProdBaskComp->setQuantityProd('1');
            $manager->persist($ProdBaskComp);
            $manager->flush();


        // return $this->redirectToRoute('basket_compouned', ['id' => $memberId]);
        return $this->render('members/basketCompounedMembers.html.twig', [
            'id' => $id,
            'basketMember' => $basketMember,//en lien avec la ligne 49
            'prodByUnity' => $prodByUnity,//en lien à la ligne 38
            'prodByKg' => $prodByKg//en lien à la ligne 39
        ]);
    }
}