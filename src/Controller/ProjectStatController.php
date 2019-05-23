<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectStatType;
use App\Service\PendingPdfManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProjectStatController extends Controller
{
    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_USER")
     * @Route("/log/projectStat/show/{id}", name="projectStat_show")
     */
    public function showProjectStat(Project $project)
    {
        return $this->render('project_stat/projectStat_show.html.twig', [
            'project' => $project
        ]);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/projectStat/add/{id}", name="projectStat_add")
     */
    public function addProjectStat(Project $project, Request $request, ObjectManager $manager, Session $session, PendingPdfManager $pendingPdfManager)
    {
        // Gestion des input file du projet (image)
        for ($i = 1; $i <= 3; $i++) {
            $session->remove('fileName' . $i);
            $getImg = 'getImg' . $i;
            $setImg = 'setImg' . $i;
            $fileName = $project->$getImg();
            if (!is_null($fileName)) {
                $file = new File($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName() . '/' . $fileName);
                $project->$setImg($file);
                $session->set('fileName' . $i, $fileName);
            }
        }

        $form = $this->createForm(ProjectStatType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            for ($j = 1; $j <= 3; $j++) {
                $setImg = 'setImg' . $j;
                if ($session->get('fileName' . $j)) {
                    $project->$setImg($session->get('fileName' . $j));
                }
            }
            $pendingPdfManager->addPendingPdf($project);
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('projectStat_show', [
                'id' => $project->getId()
            ]);
        }
        return $this->render('project_stat/projectStat_add.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/projectStat/edit/{id}", name="projectStat_edit")
     */
    public function editProjectStat(Project $project, Request $request, ObjectManager $manager, Session $session, PendingPdfManager $pendingPdfManager)
    {
        // Gestion des input file du projet (image)
        for ($i = 1; $i <= 3; $i++) {
            $session->remove('fileName' . $i);
            $getImg = 'getImg' . $i;
            $setImg = 'setImg' . $i;
            $fileName = $project->$getImg();
            if (!is_null($fileName)) {
                $file = new File($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName() . '/' . $fileName);
                $project->$setImg($file);
                $session->set('fileName' . $i, $fileName);
            }
        }

        $form = $this->createForm(ProjectStatType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            for ($j = 1; $j <= 3; $j++) {
                $setImg = 'setImg' . $j;
                if ($session->get('fileName' . $j)) {
                    $project->$setImg($session->get('fileName' . $j));
                }
            }

            foreach ($project->getState() as $state) {
                if (is_null($state->getQuantity())) {
                    $project->removeState($state);
                    $manager->remove($state);
                }
            }
            $pendingPdfManager->addPendingPdf($project);
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('projectStat_show', [
                'id' => $project->getId()
            ]);
        }

        return $this->render('project_stat/projectStat_add.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }
}
