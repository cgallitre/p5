<?php

namespace App\Controller;

use App\Repository\PortfolioRepository;
use App\Repository\TestimonialRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * Page d'accueil
     * @Route("/", name="homepage")
     */
    public function Home(PortfolioRepository $portfolioRepo, TestimonialRepository $testimonialRepo){

        $portfolios = $portfolioRepo->findAll();
        $testimonials = $testimonialRepo->findByPublished(1);

        return $this->render(
            'home.html.twig',
            [
                'portfolios' => $portfolios,
                'testimonials' => $testimonials
            ]
        );
    }

}