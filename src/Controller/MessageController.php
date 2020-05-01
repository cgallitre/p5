<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages/liste", name="message_list")
     * 
     * @return Response
     */
    public function list(MessageRepository $repo)
    {
        $messages = $repo->findAll();

        return $this->render('message/list.html.twig', [
            'messages' => $messages
        ]);
    }

/** Create a message
*
* @Route("/messages/ajout", name="message_create")
* @IsGranted("ROLE_USER")
* 
* @return Response
*/
public function create(Request $request, EntityManagerInterface $manager)
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

            return $this->redirectToRoute('message_list');
        }

        return $this->render('message/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

/**
     * Edition d'un message
     *
     * @Route("/messages/{id}/edit", name = "message_edit"
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

            return $this->redirectToRoute('message_list');
        }

        return $this->render('message/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * Suppression d'un message
     *
     * @Route("/messages/{id}/delete", name="message_delete")
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

        return $this->redirectToRoute('message_list');
    }
}