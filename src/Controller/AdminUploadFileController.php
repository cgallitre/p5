<?php

namespace App\Controller;

use App\Entity\UploadFile;
use App\Form\UploadFileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUploadFileController extends AbstractController
{
    /**
     * @Route("/admin/upload", name="admin_upload_file")
     */
    public function index(Request $request, EntityManagerInterface $manager)
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

            return $this->redirectToRoute('admin_upload_file');
        }

        return $this->render('admin/upload_file/index.html.twig', [
          'form' => $form->createView()
        ]);
    }
}
