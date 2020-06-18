<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProjectController extends AbstractController
{
    /**
     * @Route("/admin/project", name="admin_project_index")
     */
    public function index(ProjectRepository $repo)
    {
        return $this->render('admin/project/index.html.twig', [
            'projects' => $repo->findAll()
        ]);
    }

    /**
     * Create a project
     *
     * @Route("/admin//project/new", name="admin_project_new")
     * 
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le projet <strong>{$project->getTitle()}</strong> a bien été ajouté."
            );

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->render('admin/project/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * Edition d'un projet
     *
     * @Route("/admin/project/{id}/edit", name = "admin_project_edit"
     * )
     * @return Response
     */
    public function edit(Project $project, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           
            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'success',
                "La modification du projet <strong>{$project->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->render('admin/project/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * Suppression d'un projet
     *
     * @Route("/admin/project/{id}/delete", name="admin_project_delete")
     * 
     * @return Response
     */
    public function delete(Project $project, EntityManagerInterface $manager)
    {
        $countMessages = $project->getMessages()->count();
        
        if ($countMessages == 0) {
            $manager->remove($project);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "Le projet <strong>{$project->getTitle()}</strong> a bien été supprimé."
            );
        } else {
                $this->addFlash(
                    'danger',
                    "Le projet <strong>{$project->getTitle()}</strong> contient $countMessages message(s) et ne peut pas être supprimé."
                );

        }

        return $this->redirectToRoute('admin_project_index');
    }
}
