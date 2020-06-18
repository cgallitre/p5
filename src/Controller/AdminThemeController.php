<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminThemeController extends AbstractController
{
    /**
     * @Route("/admin/theme", name="admin_theme_index")
     */
    public function index(ThemeRepository $repo)
    {
        return $this->render('admin/theme/index.html.twig', [
            'themes' => $repo->findAll()
        ]);
    }

    /**
     * Create a theme
     *
     * @Route("/admin//themes/ajout", name="admin_theme_new")
     * 
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $theme = new Theme();

        $form = $this->createForm(ThemeType::class, $theme);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $manager->persist($theme);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le thème <strong>{$theme->getTitle()}</strong> a bien été ajouté."
            );

            return $this->redirectToRoute('admin_theme_index');
        }

        return $this->render('admin/theme/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * Edition d'un thème
     *
     * @Route("/admin/themes/{id}/edit", name = "admin_theme_edit"
     * )
     * @return Response
     */
    public function edit(Theme $theme, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ThemeType::class, $theme);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           
            $manager->persist($theme);
            $manager->flush();

            $this->addFlash(
                'success',
                "La modification du thème <strong>{$theme->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('admin_theme_index');
        }

        return $this->render('admin/theme/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * Suppression d'un thème
     *
     * @Route("/admin/themes/{id}/delete", name="admin_theme_delete")
     * 
     * @return Response
     */
    public function delete(Theme $theme, EntityManagerInterface $manager)
    {
        $countTraining = $theme->getTrainings()->count();

        if ($countTraining == 0){
            $manager->remove($theme);
            $manager->flush();
    
        } else {
                $this->addFlash(
                    'danger',
                    "Le thème <strong>{$theme->getTitle()}</strong> est associé à $countTraining formation(s) et ne peut pas être supprimé."
                );
        }

        return $this->redirectToRoute('admin_theme_index');
    }

}
