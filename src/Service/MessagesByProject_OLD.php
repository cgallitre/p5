<?php

namespace App\Service;

use App\Repository\MessageRepository;
use App\Repository\ProjectRepository;

class MessagesByProject
{
    private $projectRepo;
    private $messageRepo;

    public function __construct(ProjectRepository $projectRepo, MessageRepository $messageRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->messageRepo = $messageRepo;
    } 

    /**
     * 
     * Renvoie les messages correspondant au projet
     *
     */
    public function getMessages($projectId, $user)
    {

        $userProjects = $user->getProjects();

        if ($projectId){
            // on affiche le projet sélectionné
            $project = $this->projectRepo->findById($projectId);
        } else {
            // on détermine un projet par défaut
            $project = $this->projectRepo  ->createQueryBuilder('p')
                                    ->select('p')
                                    ->orderBy('p.id', 'DESC')
                                    ->setMaxResults(1)
                                    ->getQuery()
                                    ->getResult()
                                    ;
        }

        $messages = $this->messageRepo->findByProject($project);
        return [
            'messages' => $messages,
            'project' => $project
        ];
    }
}