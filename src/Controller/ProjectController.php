<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProjectController extends Controller
{
    /**
     * @param ProjectRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/log/project", name="project_list")
     */
    public function listProjects(ProjectRepository $repo)
    {
        $projects = $repo->findAllWithRelation();

        return $this->render('project/project_list.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/admin/project/add", name="project_add")
     */
    public function addProject(Request $request, ObjectManager $manager)
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $project->getImg1();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('project_images_directory'),
                $fileName
            );
            $project->setImg1($fileName);
            /*
            for($i = 1; $i <= 3; $i++)
            {
                $file = $form['img'.$i]->getData();
                if(!is_null($file))
                {
                    $file->move('img/project', $file->getClientOriginalName());
                    $setImg = 'setImg'.$i;
                    $project->$setImg($file->getClientOriginalName());
                }
            }*/
            $project->setCreated(new \DateTime('now'));
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/project_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/log/project/{id}", name="project_one")
     */
    public function oneProject(Project $project)
    {
        return $this->render('project/project_one.html.twig', [
            'project' => $project
        ]);
    }

    /**
     * @param Project $project
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/project/delete/{id}", name="project_delete")
     * @ParamConverter("project", options={"exclude": {"manager"}})
     */
    public function deletePoject(Project $project, ObjectManager $manager)
    {
        $manager->remove($project);
        $manager->flush();

        return $this->redirectToRoute('project_list');
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/project/update/{id}", name="project_update")
     * @ParamConverter("project", options={"exclude": {"request","manager"}})
     */
    public function updateProject(Project $project, Request $request, ObjectManager $manager)
    {
        $project->setImg1(new File($this->getParameter('project_images_directory').'/'.$project->getImg1()));
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($project);
            $manager->flush();

            return $this->render('project/project_one.html.twig', [
                'project' => $project
            ]);
        }

        return $this->render('project/project_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
