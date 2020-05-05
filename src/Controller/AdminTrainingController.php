<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTrainingController extends AbstractController
{
   /**
     * Create a training
     *
     * @Route("/admin/formations/ajout", name="admin_training_new")
     * 
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $training = new Training();

        $form = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $manager->persist($training);
            $manager->flush();

            $this->addFlash(
                'success',
                "La formation <strong>{$training->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('admin_training_index');
        }

        return $this->render('admin/training/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * List all training for editing
     *
     * @Route("/admin/formations", name="admin_training_index")
     * @return Response
     */
    public function list(TrainingRepository $repo) {

        $trainings = $repo->findAll();

        return $this->render('admin/training/index.html.twig', [
            'trainings' => $trainings
        ]);
    }

    /**
     * Edition d'une formation
     *
     * @Route("/admin/formations/{slug}/edit", name = "admin_training_edit"
     * )
     * @return Response
     */
    public function edit(Training $training, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           
            $manager->persist($training);
            $manager->flush();

            $this->addFlash(
                'success',
                "La modification de la formation <strong>{$training->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('admin_training_index');
        }

        return $this->render('admin/training/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * Suppression d'une formation
     *
     * @Route("/admin/formations/{slug}/delete", name="admin_training_delete")
     * 
     * @return Response
     */
    public function delete(Training $training, EntityManagerInterface $manager)
    {
       
        $manager->remove($training);
        $manager->flush();

        $this->addFlash(
            'success',
            "La formation <strong>{$training->getTitle()}</strong> a bien été supprimée."
        );

        return $this->redirectToRoute('admin_training_index');
    }
}
