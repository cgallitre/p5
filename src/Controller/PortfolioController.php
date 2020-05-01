<?php

namespace App\Controller;

use App\Repository\PortfolioRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PortfolioController extends AbstractController
{
    /**
     * Show all portfolio
     * 
     * @Route("/portfolio", name="portfolio_index")
     */
    public function index(PortfolioRepository $repo)
    {
        $portfolios = $repo->findAll();

        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolios
        ]);
    }
}