<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PortfolioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PortfolioController extends AbstractController
{
    /**
     * Show all portfolios
     * 
     * @Route("/portfolio", name="portfolio_index")
     * @Route("/portfolio/filtre/{id}", name="portfolio_filter")
     */
    public function index(PortfolioRepository $repoPortfolio, CategoryRepository $repoCategory, $id=null, Request $request)
    {

        $portfolios = $repoPortfolio
                ->createQueryBuilder('p')
                ->select('p')
                ->orderBy('p.id', 'DESC');
      
        if ($id)
        {
            $portfolios->andWhere('p.category =' . $id);
        } 

        $categories = $repoCategory->findAll();

        // if ($request->isXmlHttpRequest()){

        //     return new JsonResponse([
        //         'content' => $this->renderView('training/_list.html.twig', [
        //             'trainings' => $trainings
        //             ]),
        //         'form' => $this->renderView('training/_form.html.twig', [
        //             'themes' => $themes,
        //             'idSource' => $id
        //             ])
        //         ]);
        // }

        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolios->getQuery()->getResult(),
            'categories' => $categories,
            'idSource' => $id
        ]);
    }
}