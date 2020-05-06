<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\Pagination;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMessageController extends AbstractController
{
    /**
     * @Route("/admin/messages/{page}", name="admin_message_index", requirements={"page" : "\d+"} )
     * 
     * @return Response
     */
    public function index(MessageRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(Message::class)
                   ->setCurrentPage($page);

        return $this->render('admin/message/index.html.twig', [
            'pagination' => $pagination
            ]);
    }

/** Create a message
*
* @Route("/admin/messages/ajout", name="admin_message_new")
* @IsGranted("ROLE_USER")
* 
* @return Response
*/
public function new(Request $request, EntityManagerInterface $manager)
    {
    
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $message->setAuthor($this->getUser());
            
            $manager->persist($message);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le message été ajouté. Une réponse vous sera donnée rapidement."
            );

            return $this->redirectToRoute('admin_message_index');
        }

        return $this->render('admin/message/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

/**
     * Edition d'un message
     *
     * @Route("/admin/messages/{id}/edit", name = "admin_message_edit"
     * )
     * @return Response
     */
    public function edit(Message $message, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           
            $manager->persist($message);
            $manager->flush();

            $this->addFlash(
                'success',
                "La modification du message a bien été enregistrée."
            );

            return $this->redirectToRoute('admin_message_index');
        }

        return $this->render('admin/message/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * Suppression d'un message
     *
     * @Route("/admin/messages/{id}/delete", name="admin_message_delete")
     * 
     * @return Response
     */
    public function delete(Message $message, EntityManagerInterface $manager)
    {
    
        $manager->remove($message);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le message <strong>{$message->getTitle()}</strong> a bien été supprimé."
        );

        return $this->redirectToRoute('admin_message_index');
    }
}