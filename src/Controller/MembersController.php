<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ProdOfWeek;

class MembersController extends AbstractController
{
        /**
     * @Route("/members", name="my_compte")
     */
    public function showListProdOfWeek()
    {
        $repo = $this->getDoctrine()->getRepository(ProdOfWeek::class);
        $prodOfWeek = $repo->findAll();
        return $this->render('members/accountMembers.html.twig', [
            'prodOfWeek' => $prodOfWeek
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