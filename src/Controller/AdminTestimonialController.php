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

class AdminTestimonialController extends AbstractController
{
    /**
     * Liste tous les témoignages
     * 
     * @Route("/admin/temoignages", name="admin_testimonial_index")
     */
    public function list(TestimonialRepository $repo)
    {

        $testimonials = $repo->findAll();

        return $this->render('admin/testimonial/index.html.twig', [
            'testimonials' => $testimonials
        ]);
    }

    /**
     * Ajout d'un témoignage
     *
     * @Route("/admin/temoignages/ajout", name="admin_testimonial_new")
     * @return response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $testimonial = new Testimonial();

        $form = $this->createForm(TestimonialType::class, $testimonial)
        // Ajout ici pour ne pas que le client puisse publié son témoignage tout seul
        ->add('published', CheckboxType::class, [ 
            'label' => 'Publié',
            'required' => false
            ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();
            $manager->persist($testimonial);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le témoignage a bien été enregistré."
            );

            return $this->redirectToRoute('admin_testimonial_index');
        }

        return $this->render('admin/testimonial/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    

    /**
     * Modification d'un témoignage
     * 
     * @Route("/admin/temoignages/{id}/edit", name="admin_testimonial_edit")
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
                            ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();
            $manager->persist($testimonial);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le témoignage de <strong>{$testimonial->getAuthor()}</strong> a bien été modifié."
            );

            return $this->redirectToRoute('admin_testimonial_index');
        }


        return $this->render('admin/testimonial/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'un témoignage
     * @Route("/admin/temoignages/{id}/delete", name="admin_testimonial_delete")
     *
     * @return void
     */
    public function delete(Testimonial $testimonial, EntityManagerInterface $manager)
    {
        $manager->remove($testimonial);
        $manager->flush();
    
        $this->addFlash(
                'success',
                "Le témoignage de <strong>{$testimonial->getAuthor()}</strong> a bien été supprimé."
            );
    
            return $this->redirectToRoute('admin_testimonial_index');
    }
}
