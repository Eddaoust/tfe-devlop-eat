<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends Controller
{
    /**
     * @param ProjectRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/project", name="project_list")
     */
    public function listProjects(ProjectRepository $repo)
    {
        $projects = $repo->findAllWithLink();

        return $this->render('project/project_list.html.twig', [
            'projects' => $projects
        ]);
    }
}
