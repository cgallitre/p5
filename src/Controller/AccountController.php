<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * Permet de se connecter
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login()
    {
        return $this->render('account/login.html.twig');
    }

    /**
     * permet de se déconnecter
     *
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout()
    {
        // ne fait rien !
    }
}
