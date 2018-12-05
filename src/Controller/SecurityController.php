<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Members;

use App\Form\RegistrationAdminType;
use App\Repository\MembersRepository;
use App\Form\RegistrationBaskCollType;
use App\Form\RegistrationBaskCompType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{

    /**
     * fonction qui affiche le formulaire d'inscription au panier composés
     * 
     * @param object $request
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param interface
     * permet d'encoder les mots de passe
     * 
     * @Route("/inscriptionBaskComp", name="security_registration_bask_comp")
     */
    public function registrationBaskComp(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
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

            return $this->redirectToRoute('security_login_members');
            }

        return $this->render('security/registrationBaskComp.html.twig', [
            'formOne' => $form->createView()
        ]);
    }

    /**
     * fonction qui affiche le formulaire d'inscription au panier collectés 
     * 
     * @param object $request
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param interface
     * permet d'encoder les mots de passe
     * 
     * @Route("/inscriptionBaskColl", name="security_registration_bask_coll")
     */
    public function registrationBaskColl(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
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
    public function loginMember(){
        return $this->render('security/loginMembers.html.twig');
    }

    /**
     * fonction permettant la déconnexion à l'espace membre puis la re-direction sur la page d'accueil du site
     * 
     * @Route("/deconnexionMembres", name="security_deconnect_member")
     */
    public function deconnectMember(){}

    /**
     * @Route("/inscriptionAdmin", name="security_registration_admin")
     */
    public function registrationAdmin(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){

        $admin = new Admin();
        
        $form = $this->createForm(RegistrationAdminType::class, $admin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($admin, $admin->getPassword());

            $admin->setPassword($hash);
            $manager->persist($admin);
            $manager->flush();

            return $this->redirectToRoute('security_login_admin');
        }

        return $this->render('security/registrationAdmin.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * fonction qui permet d'afficher le formulaire d'authentification de l'administrateur
     * 
     * @Route("/connexionAdmin", name="security_login_admin")
     */
    public function loginAdmin(){
        return $this->render('security/loginAdmin.html.twig');
    }

    /**
     * fonction permettant la déconnexion à l'espace d'administration puis la re-direction sur la page d'accueil du site
     * 
     * @Route("/deconnexionAdmin", name="security_deconnect_admin")
     */
    public function deconnectAdmin(){}
}
