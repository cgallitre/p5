<?php

namespace App\Controller;

use App\Entity\Testimonial;
use App\Form\TestimonialType;
use App\Repository\TestimonialRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestimonialController extends AbstractController
{
    /**
     * Liste tous les témoignages
     * 
     * @Route("/temoignages/liste", name="testimonial_list")
     */
    public function list(TestimonialRepository $repo)
    {

        $testimonials = $repo->findAll();

        return $this->render('testimonial/list.html.twig', [
            'testimonials' => $testimonials
        ]);
    }

    /**
     * Ajout d'un témoignage
     *
     * @Route("/temoignages/ajout", name="testimonial_create")
     * @return Response
     */
    public function create(Request $request)
    {

        $testimonial = new Testimonial();

        $form = $this->createForm(TestimonialType::class, $testimonial);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager(); // injection de dépendance ne fonctionne pas :-(
            $manager->persist($testimonial);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le témoignage de <strong>{$testimonial->getAuthor()}</strong> a bien été enregistré."
            );

            return $this->redirectToRoute('testimonial_index');
        }


        return $this->render('testimonial/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
