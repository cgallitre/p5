<?php

namespace App\Controller;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrainingController extends AbstractController
{
    /**
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
}
