<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Entity\Members;
use Symfony\Component\HttpFoundation\Session\Session;


class VisitorController extends AbstractController
{
    /**
     * @Route("/", name="visitor_home")
     */
    public function homeUsers()
    {
        return $this->render('visitors/homeVisitor.html.twig');
    }

    /**
     * @Route("/presentation", name="visitor_presentation")
     */
    public function presentation()
    {
        return $this->render('visitors/presentationVisitor.html.twig');
    }

    /**
     * fonction qui permet de s'inscrire aux paniers composés
     * 
     * @param object $request
     * @param object $manager
     * parameter converter permettant de manipuler des données
     * 
     * @Route("/inscriptionBaskComp", name="inscription_bask_comp")
     */
    public function inscriptionBaskComp(Request $request, ObjectManager $manager)
    {
        $member = new Members();

        $formOne = $this->createFormBuilder($member)
            ->add('name',TextType::class)
            ->add('firstName',TextType::class)
            ->add('email', EmailType::class)
            ->add('town',TextType::class)
            ->add('pseudo',TextType::class)
            ->add('password', PasswordType::class)
            ->getForm();

        $formOne->handleRequest($request);

        if($formOne->isSubmitted() && $formOne->isValid()) {
            $member->setbaskettype("composés");
            $member->setnumberBasketRest(0);
            $member->setdayOfWeek("mardi");
            $member->setCreatedAt(new \DateTime());

            $manager->persist($member);
            $manager->flush();

            //version symfony
            $session = new Session();
            $memberId = $member->getId();
            $session->set('memberId', $memberId);

            return $this->redirectToRoute('my_compte');

        }
        return $this->render('visitors/inscriptionVisitorBaskComp.html.twig', [
            'formOne' => $formOne->createView()
        ]);
    }

    /**
     * fonction qui permet de s'inscrire aux paniers collectés
     * 
     * @param object $request
     * @param object $manager
     * parameter converter permettant de manipuler des données
     * 
     * @Route("/inscriptionBaskColl", name="inscription_bask_coll")
     */
    public function inscriptionBaskColl(Request $request, ObjectManager $manager)
    {
        $member = new Members();

        /*paniers collectés*/
        $formTwo = $this->createFormBuilder($member)
            ->add('name',TextType::class)
            ->add('firstName',TextType::class)
            ->add('email', EmailType::class)
            ->add('town',TextType::class)
            ->add('dayOfWeek',TextType::class)
            ->add('pseudo',TextType::class)
            ->add('password', PasswordType::class)
            ->getForm();

        $formTwo->handleRequest($request);

        if($formTwo->isSubmitted() && $formTwo->isValid()) {
            $member->setbaskettype("collectés");
            $member->setnumberBasketRest(0);
            $member->setCreatedAt(new \DateTime());

            $manager->persist($member);
            $manager->flush();

            //version symfony
            $session = new Session();
            $memberId = $member->getId();
            $session->set('memberId', $memberId);
            
            return $this->redirectToRoute('my_compte');
        }

        return $this->render('visitors/inscriptionVisitorBaskColl.html.twig', [
            'formTwo' => $formTwo->createView()
        ]);
    }
}
