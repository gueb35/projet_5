<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Members;

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

        $formOne = $this->createFormBuilder($member)
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('town')
            ->add('pseudo')
            ->add('password')
            ->getForm();

        $formOne->handleRequest($request);

        if($formOne->isSubmitted() && $formOne->isValid()) {
            $member->setbaskettype("composés");
            $member->setnumberBasketRest(0);
            $member->setdayOfWeek("mardi");
            $member->setCreatedAt(new \DateTime());

            $manager->persist($member);
            $manager->flush();

            return $this->redirectToRoute('my_compte');

        }

        $formTwo = $this->createFormBuilder($member)
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('town')
            ->add('dayOfWeek')
            ->add('pseudo')
            ->add('password')
            ->getForm();

        $formTwo->handleRequest($request);

        if($formTwo->isSubmitted() && $formTwo->isValid()) {
            $member->setbaskettype("collectés");
            $member->setnumberBasketRest(0);
            $member->setCreatedAt(new \DateTime());

            $manager->persist($member);
            $manager->flush();
            
            return $this->redirectToRoute('my_compte');

        }

        return $this->render('users/inscriptionUsers.html.twig', [
            'formOne' => $formOne->createView(),
            'formTwo' => $formTwo->createView()
        ]);
    }

    /**
     * @Route("/members_access", name="users_members")
     */
    public function membersAccess()
    {
        return $this->render('users/membersUsers.html.twig');
    }
    /**
     * @Route("/administrator", name="users_administrator")
     */
    public function administratorAccess()
    {
        return $this->render('users/administratorUsers.html.twig');
    }
}
