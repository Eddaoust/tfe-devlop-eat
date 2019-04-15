<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProjectController extends Controller
{
	/**
	 * @param ProjectRepository $repo
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/log/project", name="project_list")
	 */
	public function listProjects (ProjectRepository $repo)
	{
		$projects = $repo->findAllWithRelation();

		return $this->render('project/project_list.html.twig', [
			'projects' => $projects
		]);
	}

	/**
	 * @param Request       $request
	 * @param ObjectManager $manager
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/admin/project/add", name="project_add")
	 */
	public function addProject (Request $request, ObjectManager $manager)
	{
		$project = new Project();

		$form = $this->createForm(ProjectType::class, $project);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			for ($i = 1; $i <= 3; $i++) {
				$getImg = 'getImg' . $i;
				$file = $project->$getImg();
				if (!is_null($file)) {
					$fileName = md5(uniqid()) . '.' . $file->guessExtension();
					$file->move(
						$this->getParameter('project_images_directory'),
						$fileName);
					$setImg = 'setImg' . $i;
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
	 * @return Response
	 * @Route("/log/project/to-pdf/{id}", name="project_pdf")
	 */
	public function toPdf (Project $project)
	{
		$knp = $this->container->get('knp_snappy.pdf');
        $html = $this->renderView('project/project_pdf.html.twig', [
            'project' => $project,
            'rootDir' => $this->get('kernel')->getProjectDir()
        ]);
        /*$knp->generateFromHtml(
            $this->renderView('project/project_pdf.html.twig', [
                'project' => $project
            ]),
            'pdf/' . $project->getName() . '.pdf'
        );*/

        return new PdfResponse(
            $knp->getOutputFromHtml($html),
            'file.pdf'
        );

        /*return $this->render('project/project_pdf.html.twig', [
            'project' => $project
        ]);*/
	}

	/**
	 * @param Project $project
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/log/project/{id}", name="project_one")
	 */
	public function oneProject (Project $project)
	{
		return $this->render('project/project_one.html.twig', [
			'project' => $project
		]);
	}

	/**
	 * @param Project       $project
	 * @param ObjectManager $manager
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @Route("/admin/project/delete/{id}", name="project_delete")
	 * @ParamConverter("project", options={"exclude": {"manager"}})
	 */
	public function deletePoject (Project $project, ObjectManager $manager)
	{
		for ($i = 1; $i <= 3; $i++) {
			$getImg = 'getImg' . $i;
			if (!is_null($project->$getImg())) {
				unlink($this->getParameter('project_images_directory') . '/' . $project->$getImg());
			}
		}
		$manager->remove($project);
		$manager->flush();

		$this->addFlash('success', 'Projet supprimé avec succès');
		return $this->redirectToRoute('project_list');
	}

	/**
	 * @param Project       $project
	 * @param ObjectManager $manager
	 * @param               $img
	 * @param Filesystem    $filesystem
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @Route("/admin/project/image/delete/{id}/{img}", name="project_image_delete")
	 * @ParamConverter("project", options={"exclude": {"manager", "filesystem"}})
	 */
	public function deleteImage (Project $project, ObjectManager $manager, $id, $img, Filesystem $filesystem)
	{
		$setImg = 'set' . $img;
		$getImg = 'get' . $img;
		$filesystem->remove($this->getParameter('project_images_directory') . '/' . $project->$getImg());
		$project->$setImg(null);
		$manager->persist($project);
		$manager->flush();

		$this->addFlash('success', 'Image supprimé avec succès');
		return $this->redirectToRoute('project_update', [
			'id' => $id
		]);
	}

	/**
	 * @param Project       $project
	 * @param Request       $request
	 * @param ObjectManager $manager
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/admin/project/update/{id}", name="project_update")
	 * @ParamConverter("project", options={"exclude": {"request","manager", "session"}})
	 */
	public function updateProject (Project $project, Request $request, ObjectManager $manager, Session $session)
	{

		for ($i = 1; $i <= 3; $i++) {
			$session->remove('fileName' . $i);
			$getImg = 'getImg' . $i;
			$setImg = 'setImg' . $i;
			$fileName = $project->$getImg();
			if (!is_null($fileName)) {
				$file = new File($this->getParameter('project_images_directory') . '/' . $project->$getImg());
				$project->$setImg($file);
				$session->set('fileName' . $i, $fileName);
			}
		}

		$form = $this->createForm(ProjectType::class, $project);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			for ($j = 1; $j <= 3; $j++) {
				$getImg = 'getImg' . $j;
				$setImg = 'setImg' . $j;
				$file = $project->$getImg();

				if (!is_null($file)) {
					if ($session->get('fileName' . $j)) {
						$path = $this->getParameter('project_images_directory') . '/' . $session->get('fileName' . $j);
						unlink($path);
					}
					$fileName = md5(uniqid()) . '.' . $file->guessExtension();
					$file->move(
						$this->getParameter('project_images_directory'),
						$fileName);
					$project->$setImg($fileName);
				} else if ($session->get('fileName' . $j) && is_null($file)) {
					$project->$setImg($session->get('fileName' . $j));


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
}
