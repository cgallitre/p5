<?php

namespace App\Controller;

use App\Entity\Training;
use App\Repository\ThemeRepository;
use App\Repository\TrainingRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrainingController extends AbstractController
{
    /**
     * Show all trainings with filtering by theme
     * 
     * @Route("/formations", name="training_index")
     * @Route("/formations/filtre/{id}", name="training_filter")
     */
    public function index(TrainingRepository $repoTraining, ThemeRepository $repoTheme, $id=null)
    {
        // $repo = $this->getDoctrine()->getRepository(Training::class);
        if ($id)
        {
            $trainings=$repoTraining->findByTheme($id);
        } else {
            $trainings = $repoTraining->findAll();
        }
        
        $themes = $repoTheme->findAll();
        
        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
            'themes' => $themes,
            'idSource' => $id
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
            $trainings = $repo->findByTheme($training->getTheme());
        
            return $this->render('training/show.html.twig', [
                'training' => $training,
                'trainings' => $trainings
            ]);
        }
    }
    