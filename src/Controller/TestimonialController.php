<?php

namespace App\Controller;

use App\Entity\Testimonial;
use App\Form\TestimonialType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestimonialController extends AbstractController
{
    /**
     * Ajout d'un témoignage
     *
     * @Route("/temoignages/ajout", name="testimonial_new")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
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
                "Merci ! Votre témoignage a bien été enregistré."
            );

            return $this->redirectToRoute('message_index');
        }

        return $this->render('testimonial/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
