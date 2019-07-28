<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
//        $factory = $this->get('security.password_encoder');
//
//        $user = new User();
//
//        $user->setUsername('admin');
//        $pass = $factory->encodePassword($user, 'marvelAdmin');
//        $user->setEmail('admin@marvelApp.com');
//        $user->setPassword($pass);
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($user);
//        $em->flush();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     * */
    public function logoutAction(){

    }
}
