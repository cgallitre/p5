<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PortfolioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                ->where('p.published = TRUE')
                ->orderBy('p.screenOrder', 'ASC');
      
        if ($id)
        {
            $portfolios->andWhere('p.category =' . $id);
        } 

        $categories = $repoCategory->findInOrder();

        if ($request->isXmlHttpRequest()){

            return new JsonResponse([
                'content' => $this->renderView('portfolio/_list.html.twig', [
                    'portfolios' => $portfolios->getQuery()->getResult()
                    ]),
                'form' => $this->renderView('portfolio/_form.html.twig', [
                    'categories' => $categories,
                    'idSource' => $id
                    ])
                ]);
        }

        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolios->getQuery()->getResult(),
            'categories' => $categories,
            'idSource' => $id
        ]);
    }
}