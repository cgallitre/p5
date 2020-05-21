<?php

namespace App\Controller;

use App\Entity\UploadFile;
use App\Form\UploadFileType;
use App\Repository\UploadFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUploadFileController extends AbstractController
{

    /**
     * @Route("/admin/upload", name="admin_upload_index")
     */
    public function index(UploadFileRepository $repo)
    {
        $files = $repo->findAll();
        
        return $this->render('admin/upload/index.html.twig', [
            'files' => $files
        ]);
    }

    /**
     * @Route("/admin/upload/new", name="admin_upload_new")
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $file = new UploadFile;
        $form = $this->createForm(UploadFileType::class, $file);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($file);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le fichier <strong>{$file->getFileName()}</strong> a bien été ajouté."
            );

            return $this->redirectToRoute('admin_upload_index');
        }

        return $this->render('admin/upload/new.html.twig', [
          'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/upload/{id}/edit", name="admin_upload_edit")
     */
    public function edit(UploadFile $file, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(UploadFileType::class, $file);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           if ($file->getUploadfile())
           {
               $manager->persist($file);
            } else {
                $manager->remove($file);
            }
            
            $manager->flush();
            $this->addFlash(
                'success',
                "La modification <strong>{$file->getFileName()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('admin_upload_index');
        }

        return $this->render('admin/upload/edit.html.twig', [
            'form' => $form->createView()
         ]);
    }

    /**
     * @Route("/admin/upload/{id}/delete", name="admin_upload_delete")
     */
    public function delete(UploadFile $file, EntityManagerInterface $manager)
    {
       
        $manager->remove($file);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le fichier <strong>{$file->getFileName()}</strong> a bien été supprimé."
        );

        return $this->redirectToRoute('admin_upload_index');
    }
}
