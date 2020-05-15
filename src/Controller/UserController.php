<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MessagesByProject;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Tableau de bord client
     * 
     * @Route("/user/{projectId}", name="user_index")
     *
     */
    public function dashboard ($projectId = null, MessagesByProject $messageRepo)
    {
        $user = $this->getUser();

        $rep = $messageRepo->getMessages($projectId, $user);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'messages' => $rep['messages'],
            'projectDefault' => $rep['project']
        ]);
    }
}
