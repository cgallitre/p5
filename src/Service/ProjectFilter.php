<?php

namespace App\Service;

use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;

class ProjectFilter extends Pagination
{

    private $project;

    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getPages()
    {
        $repo = $this->manager->getRepository($this->entityClass); 
        $total = count($repo->findBy([
            'project' => $this->getProject()
                ]
         ));
        $pages = ceil($total / $this->limit);
        return $pages;
    }

    public function getData()
    {
        // calcul de l'offset
        $offset =  $this->currentPage * $this->limit - $this->limit;

        // demander au repository les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([
            'project' => $this->getProject()
                ], 
            [], 
            $this->limit, 
            $offset
         );
        // Renvoyer les éléments
        return $data;
    }

}