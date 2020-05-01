<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrainingController extends AbstractController
{
    /**
     * Show all trainings
     * 
     * @Route("/formations", name="training_index")
     */
    public function index(TrainingRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(Training::class);
   
        $trainings = $repo->findAll();
     

        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
            'titlePage' => 'Formations proposées'
        ]);
    }
    
    /**
     * Create a training
     *
     * @Route("/formations/ajout", name="training_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
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

            // return $this->redirectToRoute('training_show', [
            //     'slug' => $training->getSlug()
            // ]);

            return $this->redirectToRoute('training_list');
        }

        return $this->render('training/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * List all training for editing
     *
     * @Route("/formations/liste", name="training_list")
     * @return Response
     */
    public function list(TrainingRepository $repo) {

        $trainings = $repo->findAll();

        return $this->render('training/list.html.twig', [
            'trainings' => $trainings
        ]);
    }

    /**
     * Edition d'une formation
     *
     * @Route("/formations/{slug}/edit", name = "training_edit"
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

            // return $this->redirectToRoute('training_show', [
            //     'slug' => $training->getSlug()
            // ]);

            return $this->redirectToRoute('training_list');
        }

        return $this->render('training/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * Suppression d'une formation
     *
     * @Route("/formations/{slug}/delete", name="training_delete")
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

        return $this->redirectToRoute('training_list');
    }

    /**
     * Show one training
     *
     * @Route("/formations/{slug}", name="training_show")
     * @return Response
     */
    public function show($slug, Training $training, TrainingRepository $repo)
    {
        // $training = $repo->findOneBySlug($slug);
        $trainings = $repo->findAll();

        return $this->render('training/show.html.twig', [
            'training' => $training,
            'trainings' => $trainings
        ]);
    }

}
