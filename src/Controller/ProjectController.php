<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $projects = $repo->findAllWithRelation();

        return $this->render('project/project_list.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/admin/project/add", name="project_add")
     */
    public function addProject(Request $request)
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        return $this->render('project/project_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/project/{id}", name="project_one")
     */
    public function oneProject(Project $project)
    {
        return $this->render('project/project_one.html.twig', [
            'project' => $project
        ]);
    }

}
