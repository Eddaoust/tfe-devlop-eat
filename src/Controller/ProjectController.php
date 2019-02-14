<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectStateType;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
            for($i = 1; $i <= 3; $i++)
            {
                $getImg = 'getImg'.$i;
                $file = $project->$getImg();
                if(!is_null($file))
                {
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    $file->move(
                        $this->getParameter('project_images_directory'),
                        $fileName);
                    $setImg = 'setImg'.$i;
                    $project->$setImg($fileName);
                }
            }
            $project->setCreated(new \DateTime('now'));
            $manager->persist($project);
            $manager->flush();

            $this->addFlash('success', 'Projet ajouté avec succès');
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

        $this->addFlash('success', 'Projet supprimé avec succès');
        return $this->redirectToRoute('project_list');
    }

    /**
     * @param Project $project
     * @param ObjectManager $manager
     * @param $img
     * @param Filesystem $filesystem
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/project/image/delete/{id}/{img}", name="project_image_delete")
     * @ParamConverter("project", options={"exclude": {"manager", "filesystem"}})
     */
    public function deleteImage(Project $project, ObjectManager $manager, $id, $img, Filesystem $filesystem)
    {
        $setImg = 'set'.$img;
        $getImg = 'get'.$img;
        $filesystem->remove($this->getParameter('project_images_directory').'/'.$project->$getImg());
        $project->$setImg(null);
        $manager->persist($project);
        $manager->flush();

        $this->addFlash('success', 'Image supprimé avec succès');
        return $this->redirectToRoute('project_update', [
            'id' => $id
        ]);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/project/update/{id}", name="project_update")
     * @ParamConverter("project", options={"exclude": {"request","manager", "session"}})
     */
    public function updateProject(Project $project, Request $request, ObjectManager $manager, Session $session, Filesystem $filesystem)
    {

        for($i = 1; $i <= 3; $i++)
        {
            $session->remove('fileName'.$i);
            $getImg = 'getImg'.$i;
            $setImg = 'setImg'.$i;
            $fileName = $project->$getImg();
            if (!is_null($fileName))
            {
                $file = new File($this->getParameter('project_images_directory').'/'.$project->$getImg());
                $project->$setImg($file);
                $session->set('fileName'.$i, $fileName);
            }
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            for($j = 1; $j <= 3; $j++)
            {
                $getImg = 'getImg'.$j;
                $setImg = 'setImg'.$j;
                $file = $project->$getImg();

                if(!is_null($file))
                {
                    $filesystem->remove($this->getParameter('project_images_directory').'/'.$session->get('fileName'.$j));
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    $file->move(
                        $this->getParameter('project_images_directory'),
                        $fileName);
                    $project->$setImg($fileName);
                }
                else if ($session->get('fileName'.$j))
                {
                    $project->$setImg($session->get('fileName'.$j));
                }
            }
            $manager->persist($project);
            $manager->flush();

            $this->addFlash('success', 'Projet modifié avec succès');
            return $this->render('project/project_one.html.twig', [
                'project' => $project
            ]);
        }

        return $this->render('project/project_update.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    /**
     * @param ProjectRepository $repo
     * @Route("/admin/project/dashboard/year", name="project_by_year_ajax", methods={"GET"})
     */
    public function getProjectByYear(ProjectRepository $repo)
    {
        $count = $repo->getProjectByYear();

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($count, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param ProjectRepository $repo
     * @return JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     * @Route("/admin/project/dashboard/turnover", name="project_by_turnover_ajax", methods={"GET"})
     */
    public function getProjectTurnover(ProjectRepository $repo)
    {
        $turnover = $repo->getProjectTurnover();

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($turnover, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/project/add/state/{id}", name="project_add_state")
     */
    public function addProjectState(Project $project, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(ProjectStateType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($project);
            $manager->flush();

            return $this->render('project/project_step_show.html.twig', [
                'project' => $project
            ]);
        }

        return $this->render('project/project_state.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    /**
     * @Route("/admin/project/update/state/{id}", name="project_update_state")
     */
    public function updateProjectState(Project $project, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(ProjectStateType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach ($project->getState() as $state)
            {
                if (is_null($state->getQuantity()))
                {
                    $project->removeState($state);
                    $manager->remove($state);
                }
            }
            $manager->persist($project);
            $manager->flush();

            return $this->render('project/project_step_show.html.twig', [
                'project' => $project
            ]);
        }

        return $this->render('project/project_state.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }
}
