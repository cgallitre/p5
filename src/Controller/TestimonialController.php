<?php

namespace App\Controller;

use App\Entity\Testimonial;
use App\Form\TestimonialType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TestimonialRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
    public function create(Request $request, EntityManagerInterface $manager)
    {

        $testimonial = new Testimonial();

        $form = $this->createForm(TestimonialType::class, $testimonial);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();
            $manager->persist($testimonial);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le témoignage de <strong>{$testimonial->getAuthor()}</strong> a bien été enregistré."
            );

            return $this->redirectToRoute('testimonial_list');
        }


        return $this->render('testimonial/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Modification d'un témoignage
     * 
     * @Route("/temoignages/{id}/edit", name="testimonial_edit")
     *
     * @return void
     */
    public function edit(Testimonial $testimonial, Request $request,  EntityManagerInterface $manager)
    {
        $form = $this->createForm(TestimonialType::class, $testimonial)
                        // Ajout ici pour ne pas que le client puisse publié son témoignage tout seul
                        ->add('published', CheckboxType::class, [ 
                            'label' => 'Publié',
                            'required' => false
                            ]) 
                        ;

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();
            $manager->persist($testimonial);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le témoignage de <strong>{$testimonial->getAuthor()}</strong> a bien été modifié."
            );

            return $this->redirectToRoute('testimonial_list');
        }


        return $this->render('testimonial/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'un témoignage
     * @Route("/temoignages/{id}/delete", name="testimonial_delete")
     *
     * @return void
     */
    public function delete(Testimonial $testimonial)
    {
        $manager = $this->getDoctrine()->getManager(); // injection de dépendance ne fonctionne pas :-(
        $manager->remove($testimonial);
        $manager->flush();
    
        $this->addFlash(
                'success',
                "Le témoignage de <strong>{$testimonial->getAuthor()}</strong> a bien été supprimé."
            );
    
            return $this->redirectToRoute('testimonial_list');
    }
}
