<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Members;
use App\Form\RegistrationMembers;

use App\Form\RegistrationAdminType;
use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{

    /**
     * fonction servant à l'envoi d'un mail de confirmation d'inscription
     * @param string $name
     * renvoie le nom du membre qui s'est inscrit
     * @param string $email
     * renvoie l'email du membre qui s'est inscrit
     * @param transport
     * accède à la fonctionnalité d'envoie de mail
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
     * fonction qui affiche le formulaire d'inscription pour devenir membre
     * 
     * @param object $request
     * @param object $manager
     * parameter converter pour manipuler des données
     * @param interface $encoder
     * permet d'encoder les mots de passe
     * 
     * @Route("/registrationMembers", name="security_registration_members")
     */
    public function registrationMembers(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $member = new Members();

        $form = $this->createForm(RegistrationMembers::class, $member);

        $form->handleRequest($request);

        $photo = $member->getNamePhoto();

            if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($member, $member->getPassword());
            $member->setPassword($hash);
            $member->setCreatedAt(new \DateTime());

                if($photo != null){
                    $photoProfil = md5(uniqid()).'.'.$photo->guessExtension();
                    $photo->move($this->getParameter('upload_directory'), $photoProfil);
                    $member->setNamePhoto($photoProfil);
                }
    
            $manager->persist($member);
            $manager->flush();

            //permet de renvoyer ces infos pour l'envoi du mail et à l'affichage
            $memberName = $member->getName();
            $memberEmail = $member->getEmail();

            return $this->redirectToRoute('send_mail', array('name' => $memberName, 'email' => $memberEmail));
            }

        return $this->render('security/registrationMembers.html.twig', [
            'formOne' => $form->createView()
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
     * @param repository $repoA
     * parameter converter pour parler avec la table members
     * 
     * 
     * @Route("/inscriptionAdmin", name="security_registration_admin")
     */
    public function registrationAdmin(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, AdminRepository $repoA){

        $admin = $repoA->find(4);
        // $admin = new Admin();

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
