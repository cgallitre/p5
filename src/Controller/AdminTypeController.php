<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTypeController extends AbstractController
{
    /**
     * @Route("/admin/type", name="admin_type_index")
     */
    public function index(TypeRepository $repo)
    {

        return $this->render('admin/type/index.html.twig', [
            'types' => $repo->findAll()
        ]);
    }


    /**
     * Create a type
     *
     * @Route("/admin//types/ajout", name="admin_type_new")
     * 
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $type = new Type();

        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $manager->persist($type);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le type de message <strong>{$type->getTitle()}</strong> a bien été ajouté."
            );

            return $this->redirectToRoute('admin_type_index');
        }

        return $this->render('admin/type/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edition d'un type
     *
     * @Route("/admin/types/{id}/edit", name = "admin_type_edit"
     * )
     * @return Response
     */
    public function edit(Type $type, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           
            $manager->persist($type);
            $manager->flush();

            $this->addFlash(
                'success',
                "La modification du type message <strong>{$type->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('admin_type_index');
        }

        return $this->render('admin/type/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * Suppression d'un type
     *
     * @Route("/admin/types/{id}/delete", name="admin_type_delete")
     * 
     * @return Response
     */
    public function delete(Type $type, EntityManagerInterface $manager)
    {
       
        $manager->remove($type);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le type <strong>{$type->getTitle()}</strong> a bien été supprimée."
        );

        return $this->redirectToRoute('admin_type_index');
    }
}
