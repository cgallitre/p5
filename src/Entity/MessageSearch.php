<?php

namespace App\Entity;

class MessageSearch
{

    private $project;

    private $type;

    private $keyword = '';


    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getProject()
    {
        return $this->project;
    }
    public function getType()
    {
        return $this->type;
    }
}
