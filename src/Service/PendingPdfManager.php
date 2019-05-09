<?php


namespace App\Service;


use App\Entity\PendingPdf;
use App\Entity\Project;
use App\Repository\PendingPdfRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Process\Process;

/**
 * Service to manage pending pdf
 * this service ensures that there is no duplicate project id on pending pdf table
 *
 * Class PendingPdfManager
 * @package App\Service
 */
class PendingPdfManager
{
    private $pendingPdfRepository;
    private $manager;

    public function __construct(PendingPdfRepository $pendingPdfRepository, ObjectManager $manager)
    {
        $this->pendingPdfRepository = $pendingPdfRepository;
        $this->manager = $manager;
    }

    /**
     * Check for doublon
     *
     * @param Project $project
     * @return bool
     */
    private function isAlreadyPending(Project $project)
    {
        $pdf = $this->pendingPdfRepository->findOneBy(['project' => $project]);
        if ($pdf) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Persist pending pdf
     * @param Project $project
     */
    public function addPendingPdf(Project $project)
    {
        if (!$this->isAlreadyPending($project)) {
            $pendingPdf = new PendingPdf();
            $pendingPdf->setProject($project);
            $this->manager->persist($pendingPdf);
        }
    }

    /**
     * Generate the first PDF of the list and delete it for the pending list
     */
    public function generatePdf()
    {
        $pendingPdfs = $this->pendingPdfRepository->findAll();

        $process = new Process(['/usr/local/bin/node',  __DIR__ . '/../../public/js/htmlToPdf.js', $pendingPdfs[0]->getProject()->getName(), $pendingPdfs[0]->getProject()->getId()]);
        $process->run(function ($type, $buffer){
            echo $buffer;
        });

        $this->manager->remove($pendingPdfs[0]);
        $this->manager->flush();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
    }
}