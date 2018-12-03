<?php

namespace App\Controller;

use App\Entity\Members;
use App\Repository\MembersRepository;
use App\Form\RegistrationBaskCollType;

use App\Form\RegistrationBaskCompType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class SecurityController extends AbstractController
{

    /**
     * @Route("/inscriptionBaskComp", name="security_registration_bask_comp")
     */
    public function registrationBaskComp(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $member = new Members();

        $form = $this->createForm(RegistrationBaskCompType::class, $member);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($member, $member->getPassword());

            $member->setPassword($hash);

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

            return $this->redirectToRoute('security_login_members');
            }

        return $this->render('security/registrationBaskComp.html.twig', [
            'formOne' => $form->createView()
        ]);
    }

    /**
     * @Route("/inscriptionBaskColl", name="security_registration_bask_coll")
     */
    public function registrationBaskColl(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $member = new Members();

        $form = $this->createForm(RegistrationBaskCollType::class, $member);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($member, $member->getPassword());

            $member->setPassword($hash);

            $member->setbaskettype("collectés");
            $member->setnumberBasketRest(0);
            $member->setCreatedAt(new \DateTime());

            $manager->persist($member);
            $manager->flush();

            //version symfony
            $session = new Session();
            $memberId = $member->getId();
            $session->set('memberId', $memberId);

            return $this->redirectToRoute('security_login_members');
            }

        return $this->render('security/registrationBaskColl.html.twig', [
            'formTwo' => $form->createView()
        ]);
    }

    /**
     * fonction qui permet d'afficher le formulaire d'authentification
     * 
     * @Route("/connexionMembres", name="security_login_members")
     */
    public function loginMember(MembersRepository $repoM){
        // $session = new Session();
        // dump($session);
        // $memberId = $member->getId();
        // dump($memberId);
        // $session->set('memberId', $memberId);

        return $this->render('security/loginMembers.html.twig');
    }

    /**
     * fonction permettant la déconnexion à l'espace membre puis la re-direction sur la page d'acceuil du site
     * 
     * @Route("/deconnexion", name="security_deconnect_member")
     */
    public function deconnectMember()
    {
        $this->session->clear();
    }
}
