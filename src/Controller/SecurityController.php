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
     * fonction servant à l'envoi d'un mail de confirmation d'inscription
     * 
     * @Route("/sendMail/{name}/{email}", name="send_mail")
     */
    public function sendMail($name, $email, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Bonjour Mr/Mme ' . $name . '.Votre inscription est validé'))
        ->setFrom('aletsy@sfr.fr')
        ->setTo($email)
        ->setBody(
            $this->renderView(
                'emails/registration.html.twig',
                array('name' => $name)
            ),
            'text/html'
        )
    ;

    $mailer->send($message);

    return $this->render('emails/registration.html.twig',
    array('name' => $name));
    }

    /**
     * fonction qui affiche le formulaire d'inscription au panier composés
     * 
     * @param object $request
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param interface
     * permet d'encoder les mots de passe
     * @param repository $repoM
     * parameter converter pour parler avec la table members
     * 
     * @Route("/inscriptionBaskComp", name="security_registration_bask_comp")
     */
    public function registrationBaskComp(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, MembersRepository $repoM)
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

            //permet de renvoyer ces infos pour l'envoi du mail et à l'affichage
            $memberName = $member->getName();
            $memberEmail = $member->getEmail();

            return $this->redirectToRoute('send_mail', array('name' => $memberName, 'email' => $memberEmail));
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

            //permet de renvoyer ces infos pour l'envoi du mail et à l'affichage de la vue registraton.html.twig
            $memberName = $member->getName();
            $memberEmail = $member->getEmail();

            return $this->redirectToRoute('send_mail', array('name' => $memberName, 'email' => $memberEmail));
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
     * fonction qui affiche le formulaire pour définir les identifiants de l'administrateur
     * 
     * @param object $request
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param interface
     * permet d'encoder les mots de passe
     * 
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
