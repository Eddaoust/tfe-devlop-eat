<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\PendingPdfRepository;
use App\Repository\ProjectRepository;
use App\Service\PendingPdfManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Jurosh\PDFMerge\PDFMerger;

class ProjectController extends Controller
{
	/**
	 * @param ProjectRepository $repo
	 * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_ADMIN")
	 * @Route("/admin/project/add", name="project_add")
	 */
	public function addProject (Request $request, ObjectManager $manager, PendingPdfManager $pendingPdfManager)
	{
		$project = new Project();

		$form = $this->createForm(ProjectType::class, $project);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    // Save img file to a directory
			for ($i = 1; $i <= 3; $i++) {
				$getImg = 'getImg' . $i;
				$file = $project->$getImg();
				if (!is_null($file)) {
				    // Rename the file with unique name
					$fileName = md5(uniqid()) . '.' . $file->guessExtension();
					$file->move(
					    // Save file in a directory with the name of the project
                        $this->getParameter('project_images_directory') . '/' . $project->getName(),
						$fileName);
					$setImg = 'setImg' . $i;
					$project->$setImg($fileName);
				}
			}
			$project->setDirectoryName($project->getName());
			$project->setCreated(new \DateTime('now'));
			// Add the project to the pending table to be generated as PDF file
			$pendingPdfManager->addPendingPdf($project);
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
     * First route only accessible by localhost
     *
	 * @param Project $project
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/internal/project/{id}", name="project_internal")
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
     * @IsGranted("ROLE_ADMIN")
	 * @Route("/admin/project/delete/{id}", name="project_delete")
	 * @ParamConverter("project", options={"exclude": {"manager"}})
	 */
    public function deletePoject(Project $project, ObjectManager $manager, Filesystem $filesystem, PendingPdfRepository $pendingPdfRepository)
	{
	    // Remove each img file related to the project
		for ($i = 1; $i <= 3; $i++) {
			$getImg = 'getImg' . $i;
			if (!is_null($project->$getImg())) {
                unlink($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName() . '/' . $project->$getImg());
			}
		}
        $pdf = $pendingPdfRepository->findOneBy(['project' => $project]);

        // If directory is empty, delete it
        if ($this->dir_is_empty($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName())) {
            $filesystem->remove($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName());
        }
        // Remove the project to the pending table
        $manager->remove($pdf);
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
     * @IsGranted("ROLE_ADMIN")
	 * @Route("/admin/project/image/delete/{id}/{img}", name="project_image_delete")
	 * @ParamConverter("project", options={"exclude": {"manager", "filesystem"}})
	 */
	public function deleteImage (Project $project, ObjectManager $manager, $id, $img, Filesystem $filesystem)
	{
	    // Delete the given img
		$setImg = 'set' . $img;
		$getImg = 'get' . $img;
        $filesystem->remove($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName() . '/' . $project->$getImg());
		$project->$setImg(null);
		$manager->persist($project);
		$manager->flush();

		// Delete directory if is empty
        if ($this->dir_is_empty($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName())) {
            $filesystem->remove($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName());
        }

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
     * @IsGranted("ROLE_ADMIN")
	 * @Route("/admin/project/update/{id}", name="project_update")
	 * @ParamConverter("project", options={"exclude": {"request","manager", "session"}})
	 */
	public function updateProject (Project $project, Request $request, ObjectManager $manager, Session $session, PendingPdfManager $pendingPdfManager)
	{
		for ($i = 1; $i <= 3; $i++) {
			$session->remove('fileName' . $i);
			$getImg = 'getImg' . $i;
			$setImg = 'setImg' . $i;
			$fileName = $project->$getImg();
			// If already an img, get the related file
			if (!is_null($fileName)) {
                $file = new File($this->getParameter('project_images_directory') . '/' . $project->getDirectoryName() . '/' . $project->$getImg());
                // Set the file to the input
				$project->$setImg($file);
				// Add the file name to session
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

				// If already a file
				if (!is_null($file)) {
				    // Get is name in session
					if ($session->get('fileName' . $j)) {
					    // Get the path to it, and delete the old img file
                        $path = $this->getParameter('project_images_directory') . '/' . $project->getDirectoryName() . '/' . $session->get('fileName' . $j);
						unlink($path);
					}
					// Save the new file
					$fileName = md5(uniqid()) . '.' . $file->guessExtension();
					$file->move(
                        $this->getParameter('project_images_directory') . '/' . $project->getDirectoryName(),
						$fileName);
					$project->$setImg($fileName);
					// if img dont change set the older name
				} else if ($session->get('fileName' . $j) && is_null($file)) {
					$project->$setImg($session->get('fileName' . $j));
				}
			}
            $pendingPdfManager->addPendingPdf($project);
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
     * @Route("/admin/pdf/list", name="project_pdf")
     */
	public function pdfList(ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAllWithRelation();

        return $this->render('project/project_pdf_list.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * Get one pdf file
     *
     * @param Project $project
     * @return BinaryFileResponse
     * @Route("/log/pdf/download/{id}", name="project_pdf_dl")
     */
    public function downloadPdf(Project $project)
    {
        //TODO check security
        $file = $this->getParameter('pdf_directory').'/'.$project->getName().'.pdf';
        return new BinaryFileResponse($file);
    }

    /**
     * Get the merged pdf
     *
     * @param Request $request
     * @param ProjectRepository $projectRepository
     * @return BinaryFileResponse
     * @throws \Exception
     * @Route("/log/api/project/merge-pdf", name="api_project_pdf_merge")
     */
    public function mergePdf(Request $request, ProjectRepository $projectRepository)
    {
        //TODO change route & check security
        $pdf = new PDFMerger();
        $pdf->addPDF($this->getParameter('pdf_directory').'/merge/default.pdf', 'all');
        $projectIds = $request->request->get('projects');
        $projects = $projectRepository->findBy(['id' => $projectIds]);
        foreach ($projects as $project) {
            $pdf->addPDF($this->getParameter('pdf_directory').'/'.$project->getName().'.pdf', 'all');
        }
        $pdf->merge('file', $this->getParameter('pdf_directory').'/merge/merge.pdf');

        $response =  new BinaryFileResponse($this->getParameter('pdf_directory').'/merge/merge.pdf');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Private function to check if file directory is empty
     *
     * @param $dirname
     * @return bool
     */
    private function dir_is_empty($dirname)
    {
        if (!is_dir($dirname)) return false;
        foreach (scandir($dirname) as $file) {
            if (!in_array($file, array('.', '..', '.svn', '.git'))) return false;
        }
        return true;
    }
}
