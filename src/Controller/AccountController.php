<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet de se connecter
     * 
     * @Route("/connexion", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' =>$username
        ]);
    }

    /**
     * permet de se déconnecter
     *
     * @Route("/deconnexion", name="account_logout")
     * @return void
     */
    public function logout()
    {
        // ne fait rien !
    }

    /**
     * MAJ du mot de passe
     * 
     * @Route("/admin/mot-de-passe", name="admin_account_password")
     * @Route("/mot-de-passe", name="account_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $passwordUpdate = new PasswordUpdate;

        $user = $this->getUser();
        $roles = $user->getRoles();
        in_array('ROLE_ADMIN',$roles) ? $roleAdmin = true : $roleAdmin = false;
        
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if (password_verify($passwordUpdate->getOldPassword(), $user->getHash()))
            {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le mot de passe a été changé."
                );
                
                if ($roleAdmin) {
                    return $this->redirectToRoute('admin_message_index');
                } else {
                    return $this->redirectToRoute('message_index');
                }
                
            } else {
                // si échec
                $form->get('oldPassword')->addError(new FormError("L'ancien mot de passe saisi est incorrect."));
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
