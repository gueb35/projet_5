<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Members;
use App\Entity\ProdOfWeek;

class MembersController extends AbstractController
{
    /**
     * @Route("/members/{id}", name="my_compte")
     * @Route("/members", name="my_compte")
     */
    public function showListProdOfWeek($id)
    {
        $repo = $this->getDoctrine()->getRepository(ProdOfWeek::class);
        $repoM = $this->getDoctrine()->getRepository(Members::class);
        $prodOfWeek = $repo->findAll();
        $memberCount = $repoM->find($id);
        return $this->render('members/accountMembers.html.twig', [
            'prodOfWeek' => $prodOfWeek,
            'memberCount' => $memberCount
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