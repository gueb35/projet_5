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


class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users_home")
     */
    public function homeUsers()
    {
        return $this->render('users/homeUsers.html.twig');
    }

    /**
     * @Route("/presentation", name="users_presentation")
     */
    public function presentation()
    {
        return $this->render('users/presentationUsers.html.twig');
    }

    /**
     * @Route("/inscription", name="users_inscription")
     */
    public function inscription(Request $request, ObjectManager $manager)
    {
        $member = new Members();

        /*paniers composés*/
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

            //version native
            // $memberId = $member->getId();//fonctionnel
            // $_SESSION['memberId'] = $memberId;//fonctionnel
            // dump($_SESSION['memberId']);exit;//fonctionnel

            //version symfony
            $session = new Session();
            $memberId = $member->getId();
            $session->set('memberId', $memberId);
            // dump($session->get('memberId'));exit;

            return $this->redirectToRoute('my_compte');

        }

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

        return $this->render('users/inscriptionUsers.html.twig', [
            'formOne' => $formOne->createView(),
            'formTwo' => $formTwo->createView()
        ]);
    }
    
    /**
     * @Route("/administrator", name="users_administrator")
     */
    public function administratorAccess(Request $request, ObjectManager $manager)
    {
        $member = new Members();

        $formFour = $this->createFormBuilder($member)
            ->add('pseudo')
            ->add('password')
            ->getForm();

        $formFour->handleRequest($request);

        if($formFour->isSubmitted() && $formFour->isValid()) {
            // $member->setbaskettype("composés");
            // $member->setnumberBasketRest(0);
            // $member->setdayOfWeek("mardi");
            // $member->setCreatedAt(new \DateTime());

            // $manager->persist($member);
            // $manager->flush();

            return $this->redirectToRoute('my_compte');

        }
        return $this->render('users/administratorUsers.html.twig', [
            'formFour' => $formFour->createView()
        ]);
    }
}
