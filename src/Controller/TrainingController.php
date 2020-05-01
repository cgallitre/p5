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
            'trainings' => $trainings,
            'titlePage' => 'Formations proposées'
        ]);
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
