<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
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
    public function inscription()
    {
        $member = new Members();

        $formOne = $this->createFormBuilder($member)
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => "votre nom"
                ]
            ])
            ->add('surname', TextType::class, [
                'attr' => [
                    'placeholder' => "votre prénom"
                ]
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'placeholder' => "votre email"
                ]
            ])
            ->add('town', TextType::class, [
                'attr' => [
                    'placeholder' => "votre lieu de résidence"
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'placeholder' => "votre pseudo"
                ]
            ])
            ->add('password', TextType::class, [
                'attr' => [
                    'placeholder' => "votre mot de passe"
                ]
            ])

            ->getForm();

        $formTwo = $this->createFormBuilder($member)
        ->add('name', TextType::class, [
            'attr' => [
                'placeholder' => "votre nom"
            ]
        ])
        ->add('surname', TextType::class, [
            'attr' => [
                'placeholder' => "votre prénom"
            ]
        ])
        ->add('email', TextType::class, [
            'attr' => [
                'placeholder' => "votre email"
            ]
        ])
        ->add('town', TextType::class, [
            'attr' => [
                'placeholder' => "votre lieu de résidence"
            ]
        ])
        ->add('dayOfWeek', TextType::class, [
            'attr' => [
                'placeholder' => "Jour ou vous désirez récupérer votre panier chaque semaine"
            ]
        ])
        ->add('pseudo', TextType::class, [
            'attr' => [
                'placeholder' => "votre pseudo"
            ]
        ])
        ->add('password', TextType::class, [
            'attr' => [
                'placeholder' => "votre mot de passe"
            ]
        ])

        ->getForm();

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
