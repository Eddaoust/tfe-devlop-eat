<?php


namespace App\Service;


use App\Entity\PendingPdf;
use App\Entity\Project;
use App\Repository\PendingPdfRepository;
use Doctrine\Common\Persistence\ObjectManager;

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
}