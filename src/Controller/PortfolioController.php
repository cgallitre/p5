<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Repository\PortfolioRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function create(Request $request, EntityManagerInterface $manager)
    {

        $portfolio = new Portfolio();

        $form = $this->createForm(PortfolioType::class, $portfolio);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();
            $manager->persist($portfolio);
            $manager->flush();

            $this->addFlash(
                'success',
                "La référence de <strong>{$portfolio->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('portfolio_list');
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

    /**
     * Modifier une référence
     *
     * @Route("/portfolio/{id}/edit", name="portfolio_edit")
     * @return Response
     */
    public function edit(Request $request, Portfolio $portfolio, EntityManagerInterface $manager)
    {
        $form = $this->createForm(PortfolioType::class, $portfolio);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();
            $manager->persist($portfolio);
            $manager->flush();

            $this->addFlash(
                'success',
                "La référence de <strong>{$portfolio->getTitle()}</strong> a bien été modifiée."
            );

            return $this->redirectToRoute('portfolio_list');
        }

        return $this->render('portfolio/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'une référence
     *
     * @Route("/portfolio/{id}/delete", name="portfolio_delete")
     * @return Response
     */
    public function delete(Portfolio $portfolio, EntityManagerInterface $manager)
    {
        // $manager = $this->getDoctrine()->getManager();
        $manager->remove($portfolio);
        $manager->flush();
    
        $this->addFlash(
                'success',
                "La référence <strong>{$portfolio->getTitle()}</strong> a bien été supprimée."
            );
    
        return $this->redirectToRoute('portfolio_list');
    }
}