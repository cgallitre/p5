<?php

namespace App\Controller;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use Symfony\Component\Routing\Annotation\Route;
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
            'trainings' => $trainings
        ]);
    }
    
    /**
     * Create a training
     *
     * @Route("/formations/ajout", name="training_create")
     * 
     * @return Response
     */
    public function create()
    {
        return $this->render('training/new.html.twig');
    }

    /**
     * Show one training
     *
     * @Route("/formations/{slug}", name="training_show")
     * @return Response
     */
    public function show($slug, Training $training)
    {
        // $training = $repo->findOneBySlug($slug);

        return $this->render('training/show.html.twig', [
            'training' => $training
        ]);
    }

}
