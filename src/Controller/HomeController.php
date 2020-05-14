<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use App\Repository\PortfolioRepository;
use App\Repository\TestimonialRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * Page d'accueil
     * @Route("/", name="homepage")
     */
    public function Home(PortfolioRepository $portfolioRepo, TestimonialRepository $testimonialRepo){

        $portfolios = $portfolioRepo->createQueryBuilder('p')
                                        ->select('p')
                                        ->orderBy('p.id', 'DESC')
                                        ->setMaxResults(3)
                                        ->getQuery()
                                        ->getResult();

        $testimonials = $testimonialRepo->findByPublished(1);

        return $this->render(
            'home.html.twig',
            [
                'portfolios' => $portfolios,
                'testimonials' => $testimonials
            ]
        );
    }

    /**
     * Page A propos
     *
     * @Route("/apropos", name="apropos_index")
     * 
     * @return void
     */
    public function apropos()
    {
        return $this->render('apropos.html.twig');
    }

    /**
     * Formulaire de contact
     * @Route("/contact", name="contact_index")
     * 
     * 
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();
        
            $email = (new Email())
            ->from($contact['email'])
            ->to('contact@gallitre.fr')
            ->subject('Contact de ' . $contact['name'])
            ->html($contact['message']);
    
            $mailer->send($email);

            $this->addFlash('success',"Votre message a bien été envoyé. Merci de votre participation.");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
}