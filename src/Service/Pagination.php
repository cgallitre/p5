<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Pagination
{
    protected $entityClass;
    protected $limit = 5;
    protected $currentPage = 1;
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getPages()
    {
        $repo = $this->manager->getRepository($this->entityClass); 
        $total = count( $repo->findAll());
        $pages = ceil($total / $this->limit);
        return $pages;
    }

    public function getData()
    {
        // calcul de l'offset
        $offset =  $this->currentPage * $this->limit - $this->limit;

        // demander au repository les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        // Renvoyer les éléments
        return $data;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }
}