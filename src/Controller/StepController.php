<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Step;
use App\Form\StepType;

class StepController extends Controller
{
    /**
     * @param Project $project
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/project/add/step/{id}", name="project_add_step")
     */
    public function addProjectStep(Project $project, Request $request, ObjectManager $manager)
    {
        $step = new Step();

        $form = $this->createForm(StepType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $project->setSteps($step);
            $manager->persist($project);
            $manager->flush();

            return $this->render('project/project_step_show.html.twig', [
                'project' => $project
                //Todo: Ajouter le nom du projet
            ]);
        }

        return $this->render('project/project_step.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/project/update/step/{id}", name="project_update_step")
     */
    public function updateProjectStep(Project $project, Request $request, ObjectManager $manager)
    {
        $step = $project->getSteps();

        $form = $this->createForm(StepType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $project->setSteps($step);
            $manager->persist($project);
            $manager->flush();

            return $this->render('project/project_step_show.html.twig', [
                'project' => $project
            ]);
        }

        return $this->render('project/project_step.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/project/show/step/{id}", name="project_step_show")
     */
    public function showStep(Project $project)
    {
        return $this->render('project/project_step_show.html.twig', [
            'project' => $project
        ]);
    }
}
