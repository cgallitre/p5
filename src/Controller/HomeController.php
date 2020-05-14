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

}