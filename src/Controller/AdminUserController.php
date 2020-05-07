<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user_index")
     */
    public function index(UserRepository $repo)
    {
        $users = $repo->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }
   /**
     * Permet de s'enregistrer
     * 
     * @Route("/admin/user/new", name="admin_user_new")
     *
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User;
        
        $form = $this->createForm(RegistrationType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le compte de <strong>{$user->getFirstName()} {$user->getLastName()}</strong> a bien été créé."
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView()
        ]);
        
    }

    /**
     * Permet de modifier son profil utilisateur
     *
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * 
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager, User $user)
    {
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données ont bien été modifiées."
            );

            return $this->redirectToRoute('admin_user_index');
        }
        
        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'un utilisateur
     *
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * 
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager)
    {
       
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le compte <strong>{$user->getFullname()}</strong> a bien été supprimé."
        );

        return $this->redirectToRoute('admin_user_index');
    }
}
