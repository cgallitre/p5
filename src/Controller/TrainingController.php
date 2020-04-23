<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
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
    public function create(Request $request)
    {
        $training = new Training();

        $form = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager(); // injection de dépendance ne fonctionne pas :-(
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
