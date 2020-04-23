<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Repository\PortfolioRepository;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * Create a portfolio
     * 
     * @Route("/portfolio/ajout", name="portfolio_create")
     *
     * @return Response
     */
    public function create(Request $request)
    {

        $portfolio = new Portfolio();

        $form = $this->createForm(PortfolioType::class, $portfolio);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager(); // injection de dépendance ne fonctionne pas :-(
            $manager->persist($portfolio);
            $manager->flush();

            $this->addFlash(
                'success',
                "La référence de <strong>{$portfolio->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('portfolio_index');
        }

        return $this->render('portfolio/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * List all portfolio for editing
     * 
     * @Route("/portfolio/liste", name="portfolio_list")
     * 
     * @return Response
     */
    public function list(PortfolioRepository $repo)
    {
        $portfolios = $repo->findAll();

        return $this->render('portfolio/list.html.twig', [
            'portfolios' => $portfolios
        ]);
    }
}
