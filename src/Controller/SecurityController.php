<?php

namespace App\Controller;

use App\Entity\Members;
use App\Repository\MembersRepository;

use App\Form\RegistrationBaskCollType;
use App\Form\RegistrationBaskCompType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/deconnexion", name="security_deconnect_member")
     */
    public function deconnectMember(){}
}
