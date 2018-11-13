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
     * @Route("/basket_compouned/{id}", name="basket_compouned")
     */
    public function basket_compouned(ProdOfWeekRepository $repo,$id)
    {
        $prodOfWeekComp = $repo->prodByUnity();//permet de récupérer tous les produits du champ proByUnity
        $prodOfWeekColl = $repo->prodByKg();//permet de récupérer tous les produits du champ proByKg
        return $this->render('members/basketCompounedMembers.html.twig', [
            'id' => $id,
            'prodOfWeekComp' => $prodOfWeekComp,
            'prodOfWeekColl' => $prodOfWeekColl
        ]);
    }
}