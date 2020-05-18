<?php

namespace App\Controller;
 
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\MessageSearch;
use App\Form\MessageSearchType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * Tableau de bord client
     * 
     * @Route("/messages", name="message_index")
     * @IsGranted("ROLE_USER")
     *
     */
    public function dashboard (MessageRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getUser();
        $search = new MessageSearch();
        $form = $this->createForm(MessageSearchType::class, $search);
        $form->handleRequest($request);

        $messages = $paginator->paginate(
            $repo->findAllQuery($search),
            $request->query->getInt('page', 1),
            5
        );
    

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /** Create a message
    *
    * @Route("/messages/ajout", name="message_new")
    * @IsGranted("ROLE_USER")
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

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edition d'un message
     *
     * @Route("/messages/{id}/edit", name = "message_edit")
     * @IsGranted("ROLE_USER")
     * 
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

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'un message
     *
     * @Route("/messages/{id}/delete", name="message_delete")
     * @IsGranted("ROLE_USER")
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

        return $this->redirectToRoute('message_index');
    }
}
