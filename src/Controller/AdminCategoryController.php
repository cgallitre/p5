<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/categorie", name="admin_category_index")
     */
    public function index(CategoryRepository $repo)
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $repo->findAll()
        ]);
    }

    /**
     * Create a category
     *
     * @Route("/admin//categories/ajout", name="admin_category_new")
     * 
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                "La catégorie <strong>{$category->getTitle()}</strong> a bien été ajoutée."
            );

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edition d'une catégorie
     *
     * @Route("/admin/categories/{id}/edit", name = "admin_category_edit"
     * )
     * @return Response
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                "La modification de la catégorie <strong>{$category->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * Suppression d'une catégorie
     *
     * @Route("/admin/categories/{id}/delete", name="admin_category_delete")
     * 
     * @return Response
     */
    public function delete(Category $category, EntityManagerInterface $manager)
    {
        $countPorfolio = $category->getPortfolios()->count();

        if ($countPorfolio == 0){
            $manager->remove($category);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "La catégorie <strong>{$category->getTitle()}</strong> a bien été supprimée."
            );
        } else {
                $this->addFlash(
                    'danger',
                    "La catégorie <strong>{$category->getTitle()}</strong> est associée à $countPorfolio référence(s) et ne peut pas être supprimée."
                );
        }

        return $this->redirectToRoute('admin_category_index');
    }
}
