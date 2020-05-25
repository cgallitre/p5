<?php

namespace App\Controller;

use App\Entity\Training;
use App\Repository\ThemeRepository;
use App\Repository\TrainingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrainingController extends AbstractController
{
    /**
     * Show all trainings with filtering by theme
     * 
     * @Route("/formations", name="training_index")
     * @Route("/formations/filtre/{id}", name="training_filter")
     */
    public function index(TrainingRepository $repoTraining, ThemeRepository $repoTheme, $id=null, Request $request)
    {
        
        if ($id)
        {
            $trainings=$repoTraining->findByTheme($id);
        } else {
            $trainings = $repoTraining->findAll();
        }
    

        $themes = $repoTheme->findAll();

        if ($request->isXmlHttpRequest()){

            return new JsonResponse([
                'content' => $this->renderView('training/_list.html.twig', [
                    'trainings' => $trainings
                    ]),
                'form' => $this->renderView('training/_form.html.twig', [
                    'themes' => $themes,
                    'idSource' => $id
                    ])
                ]);
        }
        
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
    