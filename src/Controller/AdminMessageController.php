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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMessageController extends AbstractController
{
    /**
     * @Route("/admin/messages", name="admin_message_index", requirements={"page" : "\d+"} )
     * 
     * @return Response
     */
    public function index(MessageRepository $repo, PaginatorInterface $paginator, Request $request)
    {      
        $search = new MessageSearch();
        $form = $this->createForm(MessageSearchType::class, $search);
        $form->handleRequest($request);

        $messages = $paginator->paginate(
            $repo->findAllQuery($search),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('admin/message/index.html.twig', [
            'form' => $form->createView(),
            'messages' => $messages
            ]);
    }

    /** Create a message
    *
    * @Route("/admin/messages/ajout", name="admin_message_new")
    * 
    * @return Response
    */
    public function new(Request $request, EntityManagerInterface $manager)
        {
        
            $message = new Message();

            $form = $this->createForm(MessageType::class, $message);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                foreach ($message->getUploadFiles() as $file){
                    $file->setMessage($message);
                    $manager->persist($file);
                }

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
            
                $files = $message->getUploadfiles();

                foreach ($files as $key => $file){
                    if ($file->getUploadfile()){
                        $file->setMessage($message);
                        $files->set($key,$file);
                    } else {
                        $manager->remove($file);
                    }
                }
                // foreach ($files as $key => $file){
                //     $file->setMessage($message);
                //     $files->set($key,$file);
                // }

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
            foreach ($message->getUploadFiles() as $file){
                $manager->remove($file);
            }

            $manager->remove($message);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le message <strong>{$message->getTitle()}</strong> a bien été supprimé."
            );

            return $this->redirectToRoute('admin_message_index');
        }
}