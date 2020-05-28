<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Project;
use App\Form\MessageType;
use App\Entity\MessageSearch;
use App\Form\MessageSearchType;
use Doctrine\ORM\EntityRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMessageController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_message_index", requirements={"page" : "\d+"} )
     * 
     * @return Response
     */
    public function index(MessageRepository $repo, PaginatorInterface $paginator, Request $request, EntityManagerInterface $manager)
    {      
        $search = new MessageSearch();
        // liste des projets ouverts associés au user courant
        $projects = $manager->createQuery('SELECT p FROM \App\Entity\Project p WHERE p.finished = false ')->getResult();

        $form = $this
                    ->createForm(MessageSearchType::class, $search)
                    ->add('project', ChoiceType::class, [
                        'required' => false,
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

        // Dashboard
        $users = $manager->createQuery('SELECT count(u) FROM \App\Entity\User u WHERE u.status = 1')->getSingleScalarResult();
        $testimonials = $manager->createQuery('SELECT count(t) FROM \App\Entity\Testimonial t WHERE t.published = false')->getSingleScalarResult();
        $projects = $manager->createQuery('SELECT count(p) FROM \App\Entity\Project p WHERE p.finished = false')->getSingleScalarResult();
        

        return $this->render('admin/message/index.html.twig', [
            'form' => $form->createView(),
            'messages' => $messages,
            'stats' => compact('users', 'testimonials', 'projects') // retourne un tableau clé/valeur avec chaque élément
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

            $form = $this->createForm(MessageType::class, $message)
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'title',
                'label' => 'Projet',
                'expanded' =>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                            ->createQueryBuilder('p')
                            ->where('p.finished = false')
                            ->orderBy('p.id', 'DESC');
                }
            ])
            ;

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
            $form = $this->createForm(MessageType::class, $message)
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'title',
                'label' => 'Projet',
                'expanded' =>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                            ->createQueryBuilder('p')
                            ->where('p.finished = false')
                            ->orderBy('p.id', 'DESC');
                }
            ])
            ;

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

                return $this->redirectToRoute('admin_message_index');
            }

            return $this->render('admin/message/edit.html.twig', [
                'form' => $form->createView(),
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