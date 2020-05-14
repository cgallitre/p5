<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Service\ProjectFilter;
use App\Repository\MessageRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Tableau de bord client
     * 
     * @Route("/user/{projectId}", name="user_index")
     *
     * @return void
     */
    public function dashboard (ProjectRepository $projectRepo, $projectId = null, MessageRepository $messageRepo)
    {
        $user = $this->getUser();
        $userProjects = $user->getProjects();

        if ($projectId){
            // on affiche le projet sélectionné
            $project = $projectRepo->findById($projectId);
        } else {
            // on détermine un projet par défaut
            // $project = $repo->findById($userProjects[0]);
            $project = $projectRepo  ->createQueryBuilder('p')
                                    ->select('p')
                                    ->orderBy('p.id', 'DESC')
                                    ->setMaxResults(1)
                                    ->getQuery()
                                    ->getResult()
                                    ;
        }

        $messages = $messageRepo->findByProject($project);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'messages' => $messages,
            'projectDefault' => $project
        ]);
    }
}
