<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Repository\PortfolioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPortfolioController extends AbstractController
{
   /**
     * Create a portfolio
     * 
     * @Route("/admin/portfolio/ajout", name="admin_portfolio_new")
     *
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
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

            return $this->redirectToRoute('admin_portfolio_index');
        }

        return $this->render('admin/portfolio/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * List all portfolio for editing
     * 
     * @Route("/admin/portfolio", name="admin_portfolio_index")
     * 
     * @return Response
     */
    public function list(PortfolioRepository $repo)
    {
        $portfolios = $repo->findAllInOrder();

        return $this->render('admin/portfolio/index.html.twig', [
            'portfolios' => $portfolios
        ]);
    }

    /**
     * Modifier une référence
     *
     * @Route("/admin/portfolio/{id}/edit", name="admin_portfolio_edit")
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

            return $this->redirectToRoute('admin_portfolio_index');
        }

        return $this->render('admin/portfolio/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'une référence
     *
     * @Route("/admin/portfolio/{id}/delete", name="admin_portfolio_delete")
     * 
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
    
        return $this->redirectToRoute('admin_portfolio_index');
    }
}
