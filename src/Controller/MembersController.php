<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProdOfWeekRepository;
use App\Repository\MembersRepository;
use App\Entity\Members;
use App\Entity\ProdOfWeek;

class MembersController extends AbstractController
{
    /**
     * @Route("/members/{id}", name="my_compte")
     * @Route("/members", name="my_compte")
     */
    public function showListProdOfWeek(ProdOfWeekRepository $repo, MembersRepository $repoM/*, $id*/)
    {
        // $repo = $this->getDoctrine()->getRepository(ProdOfWeek::class);
        // $repoM = $this->getDoctrine()->getRepository(Members::class);
        $prodOfWeek/*comp*/ = $repo->findAll();//ligne à remplacer par celle du dessous
        // $prodOfWeekComp = $repo->findBy('proByUnity');//doit permettre de récupérer tous les produits du champ proByUnity
        // $prodOfWeekColl = $repo->findBy('proByKg');//doit permettre de récupérer tous les produits du champ proByKg
        // $memberCount = $repoM->find($id);//doit permettre de récupérer les infos du membre identifier par l'id
        return $this->render('members/accountMembers.html.twig', [
            'prodOfWeek' => $prodOfWeek/*,*/
            // 'prodOfWeekComp' => $prodOfWeekComp,
            // 'prodOfWeekColl' => $prodOfWeekColl,
            // 'memberCount' => $memberCount
        ]);
    }
    /**
     * @Route("/basket_compouned", name="basket_compouned")
     */
    public function basket_compouned()
    {
        return $this->render('members/basketCompounedMembers.html.twig');
    }
}