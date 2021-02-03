<?php

namespace App\Service;

use App\Repository\ProjectRepository;

class Globals
{
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function portfolios()
    {
        return $this->projectRepository->findAll();

    }
}
