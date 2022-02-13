<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/registration", name="security_registration")
     */
    public function registration( UserPasswordEncoderInterface $encoder,Request $request, EntityManagerInterface $manager):Response
    {

        $user=new User();
        $form=$this->createForm(RegistrationType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $hash=$encoder->encodePassword($user,$user->getUserPassword());
            $user->setUserPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/login",name="security_login")
     */
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }
    /**
     * @Route ("/logout",name="security_logout")
     */
    public function logout(): void
    {}
}
