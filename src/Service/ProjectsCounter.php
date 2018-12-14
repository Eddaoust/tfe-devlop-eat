<?php
/**
 * Created by PhpStorm.
 * User: edmonddaoust
 * Date: 2018-12-14
 * Time: 09:37
 */

namespace App\Service;


use App\Repository\ProjectRepository;

class ProjectsCounter
{
    private $projectsCount;

    /**
     * Get the count of the projects register in database
     * projectCounter constructor.
     * @param ProjectRepository $repo
     */
    public function __construct(ProjectRepository $repo)
    {
        $projects = $repo->getCount();
        $this->projectsCount = $projects;
    }

    /**
     * @return int
     */
    public function getProjectsCount()
    {
        return $this->projectsCount;
    }

}