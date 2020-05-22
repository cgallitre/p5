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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
    public function index (MessageRepository $repo, PaginatorInterface $paginator, Request $request,  EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $search = new MessageSearch();

        // liste des projets ouverts associés au user courant
        $projects = $manager->createQuery('SELECT p FROM \App\Entity\Project p JOIN p.users u WHERE p.finished = false AND u.id=' . $user->getId() )->getResult();
        // Activation d'un projet par défaut
        $search->setProject($projects[0]);

        $form = $this
            ->createForm(MessageSearchType::class, $search)
            ->add('project', ChoiceType::class, [
                'choices' => $projects,
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => false,
            ]);

        $form->handleRequest($request);

        $messages = $paginator->paginate(
            $repo->findAllQuery($search),
            $request->query->getInt('page', 1),
            8
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
            $files = $message->getUploadfiles();

            foreach ($files as $key => $file){
                if ($file->getUploadfile()){
                    $file->setMessage($message);
                    $files->set($key,$file);
                } else {
                    $manager->remove($file);
                }
            }
        
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
        foreach ($message->getUploadFiles() as $file){
            $manager->remove($file);
        }

        $manager->remove($message);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le message <strong>{$message->getTitle()}</strong> a bien été supprimé."
        );

        return $this->redirectToRoute('message_index');
    }
}
